<?php

namespace Bsmma\Http\Controllers;

use Auth;
use Bsmma\BogoPromosToUser;
use Bsmma\CreditCardType;
use Bsmma\Http\Requests;
use Bsmma\Http\Requests\DepositProfileRequest;
use Bsmma\Http\Requests\ProfileRequest;
use Bsmma\MerchantTransaction;
use Bsmma\PaypalEmail;
use Bsmma\StripeDetail;
use Bsmma\User;
use Bsmma\UserBalance;
use Bsmma\divStrong\Transformers\ProfileTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class UsersController extends ApiController
{
    private $stripe;
    private $paypal;
    private $bogoPromosToUser;

    public function __construct(
        User $user,
        ProfileTransformer $profileTransformer,
        UserBalance $userBalance,
        MerchantTransaction $merchantTransaction,
        CreditCardType $creditCardType,
        StripeDetail $stripeDetail,
        BogoPromosToUser $bogoPromosToUser
    )
    {
    	$this->user = $user;
        $this->userBalance = $userBalance;
    	$this->profileTransformer = $profileTransformer;
        $this->merchantTransaction = $merchantTransaction;
        $this->creditCardType = $creditCardType;
        $this->stripeDetail = $stripeDetail;
        $this->bogoPromosToUser = $bogoPromosToUser;
    }

    public function getPlayerName()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return response()->json(['player_name'=>$user->player_name]);
    }

    public function getPlayerBalance()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $balance = calculatePlayersBalance($user->id);

        return response()->json(['playerBalance' => $balance/100 ]);
    }

    public function profile()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

        $userBalance     = calculatePlayersBalance($user->id)/100;
        $userTotalPoints = calculatePlayersTotalPoints($user->id);

        $userPromo = $this->bogoPromosToUser->where('user_id', $user->id)
                        ->with('bogoPromo')
                        ->where('is_complete', 0)
                        ->whereNotNull('entered_code_on')
                        ->first();

        $promo = ( is_null($userPromo) ) ? ['id' => 0, 'code' => '', 'status' => ''] : ['id' => $userPromo->id, 'code' => $userPromo->bogoPromo->code, 'status' => determineBogoStatus($userPromo, explode(',',$userPromo->bogoPromo->valid_entry_fees))];

    	return $this->respond([
            'profile' => [
                'name'    => $user->player_name,
                'email'   => $user->email,
                'avatar'  => $user->avatar,
                'balance' => $userBalance,
                'points'  => $userTotalPoints,
                'promo'   => $promo,
            ],
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->where('id', $user->id)
                        ->first();

        $userInfo->player_name = $request->player_name;
        $userInfo->avatar = $request->avatar;

        if ( $request->new_password !== '' && Auth::attempt(['email' => $user->email, 'password' => $request->old_password]) ) {
            $userInfo->password = bcrypt($request->new_password);
        }

        return ($userInfo->save()) ? $this->respond([
            'success' => true,
            'msg'     => 'Your profile was updated successfully',
        ]) : $this->respond([
            'success' => false,
            'msg'     => 'There was a problem updating your profile'
        ]);
    }

    public function depositProfile()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->with([
                            'stripeDetail',
                            'stripeDetail.creditCardType',
                            'paypalEmail'
                        ])
                        ->where('id', $user->id)
                        ->first();

        return $this->respond([
            'profile' => [
                'name'        => $user->player_name,
                'email'       => $user->email,
                'lastname'    => $user->lastname,
                'firstname'   => $user->firstname,
                'address'     => $user->address1,
                'address2'    => $user->address2,
                'city'        => $user->city,
                'state'       => $user->state,
                'zipcode'     => $user->zipcode,
                'merchant'    => $user->merchant_id,
                'stripeId'    => (is_null($userInfo->stripeDetail)) ? 0 : $userInfo->stripeDetail->stripe_id,
                'ccDigits'    => (is_null($userInfo->stripeDetail)) ? '' : $userInfo->stripeDetail->cc_digits,
                'ccImageName' => (is_null($userInfo->stripeDetail)) ? '' : $userInfo->stripeDetail->creditCardType->image_name,
                'paypalEmail' => (is_null($userInfo->paypalEmail)) ? '' : $userInfo->paypalEmail->email,
            ],
        ]);
    }

    public function depositProfileUpdate(DepositProfileRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->where('id', $user->id)
                        ->first();

        $userInfo->lastname    = $request->lastname;
        $userInfo->firstname   = $request->firstname;
        $userInfo->address1    = $request->address;
        $userInfo->address2    = $request->address2;
        $userInfo->city        = $request->city;
        $userInfo->state       = $request->state;
        $userInfo->zipcode     = $request->zipcode;
        $userInfo->merchant_id = (int)$request->merchant_id;

        return ($userInfo->save()) ? $this->respond([
            'success' => true,
            'msg' => 'Your profile was updated successfully',
        ]) : $this->respond([
            'success' => false,
            'msg' => 'There was a problem updating your profile'
        ]);
    }

    public function makeDeposit(Request $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        if ( $request->merchantId === 1 ) $outcome = $this->makeDepositWithStripe($user, $request);

        if ( $request->merchantId === 2 ) $outcome = $this->makeDepositWithPayPal($user, $request);

        if ( isset($outcome['approvalLink']) ) return $this->respond(['success' => true, 'approvalLink' => $outcome['approvalLink']]);

        return ( $outcome['success'] ) ? $this->respond([
            'success' => true,
            'msg'     => 'Your deposit was successfully processed. Your transaction number is '.$outcome['transactionId'],
        ]) : $this->respondWithError($outcome['msg']);
    }

    private function makeDepositWithStripe($user, $request)
    {
        $userInfo = $this->user->with('stripeDetail')
                        ->where('id', $user->id)
                        ->first();

        $this->stripe = new \Bsmma\divStrong\Merchants\StripeMerchant;

        $cardInfo = [
            'cardNumber' => $request->card['number'],
            'expMonth'   => $request->card['expMonth'],
            'expYear'    => $request->card['expYear'],
            'cvc'        => $request->card['cvv'],
            'address'    => $request->card['address'],
            'city'       => $request->card['city'],
            'state'      => $request->card['state'],
            'zipcode'    => $request->card['zipcode'],
        ];

        // not a current customer and not creating customer OR current customer using new card not saving new card
        if ( (! $request->customerState['isCustomer'] && ! $request->customerState['addCustomer']) || ($request->customerState['isCustomer'] && $request->customerState['currentCustomer']['newCard'] && ! $request->customerState['currentCustomer']['saveNewCard']) ) $chargeOutcome = $this->chargeCard($cardInfo, $request);

        // not a current customer create new customer
        if ( ! $request->customerState['isCustomer'] && $request->customerState['addCustomer'] ) $chargeOutcome = $this->createCustomerAndCharge($cardInfo, $userInfo, $request);

        // current customer using stored card
        if ( $request->customerState['isCustomer'] && ! $request->customerState['currentCustomer']['newCard'] ) $chargeOutcome = $this->chargeCustomer($userInfo, $request);

        // current customer using new card saving new card
        if ( $request->customerState['isCustomer'] && $request->customerState['currentCustomer']['newCard'] && $request->customerState['currentCustomer']['saveNewCard'] ) $chargeOutcome = $this->updateCustomerAndCharge($cardInfo, $userInfo, $request);

        // current customer wants to use new card and stop storing
        // if ( $request->customerState['isCustomer'] && $request->customerState['removeCustomer'] ) $chargeOutcome = $this->removeCustomerAndCharge($cardInfo, $userInfo);

        if ( ! $chargeOutcome['success'] ) return ['success' => false, 'msg' => $chargeOutcome['msg']];

        DB::transaction(function() use ($chargeOutcome, $userInfo, $user, $request) {
            $balance = $this->userBalance->create([
                'user_id'             => $user->id,
                'transaction_type_id' => 4,
                'amount'              => $request->deposit,
            ]);

            $this->merchantTransaction->create([
                'merchant_id'      => $request->merchantId,
                'user_balances_id' => $balance->id,
                'transaction_id'   => $chargeOutcome['transactionId'],
            ]);

            if ( $request->customerState['addCustomer'] ) $this->addStripeCustomer($chargeOutcome, $user);
            if ( $request->customerState['currentCustomer']['saveNewCard'] ) $this->updateStripeCustomer($chargeOutcome, $user);
        });

        return ['success' => true, 'transactionId' => $chargeOutcome['transactionId']];
    }

    private function makeDepositWithPayPal($user, $request)
    {
        $this->paypal = new \Bsmma\divStrong\Merchants\PayPalMerchant;

        $this->paypal->setChargeData($request->input());
        $this->paypal->setCredentials();
        $paymentResult = $this->paypal->createPayment();

        if ( isset($paymentResult['failed']) ) return ['success' => flase, 'msg' => $paymentResult['msg']];

        Session::put('userId', $user->id);
        Session::put('fee', $request['fee']);

        return ['success' => true, 'approvalLink' => $paymentResult['approvalLink']];
    }

    public function completePaymentWithPayPal(Request $request)
    {
        $this->paypal = new \Bsmma\divStrong\Merchants\PayPalMerchant;

        $fee = (int)Session::get('fee');
        $user = $this->user->where('id', (int)Session::get('userId'))->first();

        $input = $request->input();

        $this->paypal->setCredentials();
        $this->paypal->setChargeData($request->input());
        $chargeOutcome = $this->paypal->charge();

        if ( isset($chargeOutcome['failed']) ) return ['success' => false, 'msg' => $chargeOutcome['msg']];

        DB::transaction(function() use ($chargeOutcome, $user, $request, $fee) {
            $balance = $this->userBalance->create([
                'user_id'             => $user['id'],
                'transaction_type_id' => 6,
                'amount'              => $chargeOutcome['amount'],
            ]);

            $this->merchantTransaction->create([
                'merchant_id'      => 2,
                'user_balances_id' => $balance->id,
                'transaction_id'   => $chargeOutcome['transactionId'],
                'payment_id'       => $chargeOutcome['paymentId'],
            ]);
        });

        Session::forget('userId');
        Session::forget('fee');

        return redirect()->away(url('/play#!/deposit/paypal/'.$chargeOutcome['transactionId']));
    }

    public function paypalPaymentRejected()
    {
        return redirect()->away(url('/play#!/deposit'));
    }

    private function chargeCard($cardInfo, $request)
    {
        $this->stripe->setCardData($cardInfo);
        $tokenResponse = $this->stripe->getToken();

        if ( isset($tokenResponse['failed']) ) return ['success' => false, 'msg' => $tokenResponse['msg']];

        $chargeInfo = [
            'stripeToken' => $tokenResponse['id'],
            'amount'      => $request->amount,
            'description' => 'BSMMA Funds deposit for '.$request->card['firstname'].' '.$request->card['lastname'],
        ];

        return $this->performCharge($chargeInfo);
    }

    private function chargeCustomer($userInfo, $request)
    {
        $chargeInfo = [
            'customer'    => $userInfo['stripeDetail']['stripe_id'],
            'amount'      => $request->amount,
            'description' => 'BSMMA Funds deposit for '.$userInfo['firstname'].' '.$userInfo['lastname'],
        ];

        if ( isset($userInfo['cardBrand']) ) $chargeInfo['cardBrand'] = $userInfo['cardBrand'];

        return $this->performCharge($chargeInfo);
    }

    private function createCustomerAndCharge($cardInfo, $userInfo, $request)
    {
        $this->stripe->setCardData($cardInfo);
        $tokenResponse = $this->stripe->getToken();

        if ( isset($tokenResponse['failed']) ) return ['success' => false, 'msg' => $tokenResponse['msg']];

        $customerInfo = [
            'source'      => $tokenResponse['id'],
            'description' => 'BSMMA Customer Account',
            'metadata'    => [
                'name' => $userInfo['firstname'].' '.$userInfo['lastname'],
                'id'   => $userInfo['id'],
            ],
            'email'       => $userInfo['email'],
        ];

        $customerAccountId = $this->stripe->createCustomer($customerInfo);

        $ccBrand = $this->cardBrand($cardInfo['cardNumber']);

        $chargeInfo = [
            'customer'    => $customerAccountId,
            'amount'      => $request->amount,
            'description' => 'BSMMA Funds deposit for '.$userInfo['firstname'].' '.$userInfo['lastname'],
            'cardBrand'   => $ccBrand,
        ];

        return $this->performCharge($chargeInfo);
    }

    private function updateCustomerAndCharge($cardInfo, $userInfo, $request)
    {
        $this->stripe->setCardData($cardInfo);
        $tokenResponse = $this->stripe->getToken();

        if ( isset($tokenResponse['failed']) ) return ['success' => false, 'msg' => $tokenResponse['msg']];

        $userInfo['cardBrand'] = $this->cardBrand($cardInfo['cardNumber']);

        $this->stripe->updateCustomer([
            'customer' => $userInfo['stripeDetail']['stripe_id'],
            'source'   => $tokenResponse['id'],
        ]);

        return $this->chargeCustomer($userInfo, $request);
    }

    private function performCharge($chargeInfo)
    {
        $this->stripe->setChargeData($chargeInfo);

        $chargeResponse = $this->stripe->charge();

        if ( isset($chargeResponse['failed']) ) return ['success' => false, 'msg' => $chargeResponse['msg']];

        $return = [
            'success'       => true,
            'transactionId' => $chargeResponse['transactionId'],
            'ccDigits'      => $chargeResponse['ccDigits'],
        ];

        if ( isset($chargeInfo['customer']) ) $return['customerId'] = $chargeInfo['customer'];
        if ( isset($chargeInfo['cardBrand']) ) $return['cardBrand'] = $chargeInfo['cardBrand'];

        return $return;
    }

    private function addStripeCustomer($responseData, $user)
    {
        $cardType = $this->creditCardType->where('name', $responseData['cardBrand'])->first();

        $this->stripeDetail->insert([
            'user_id'             => $user->id,
            'stripe_id'           => $responseData['customerId'],
            'cc_digits'           => $responseData['ccDigits'],
            'credit_card_type_id' => $cardType->id,
        ]);
    }

    private function updateStripeCustomer($responseData, $user)
    {
        $cardType = $this->creditCardType->where('name', $responseData['cardBrand'])->first();

        $this->stripeDetail->where('user_id', $user->id)->update([
            'stripe_id'           => $responseData['customerId'],
            'cc_digits'           => $responseData['ccDigits'],
            'credit_card_type_id' => $cardType->id,
        ]);
    }

    /**
    * Return credit card type if number is valid
    * @return string
    * @param $number string
    **/
    private function cardBrand($number)
    {
        $number=preg_replace('/[^\d]/','',$number);
        if (preg_match('/^3[47][0-9]{13}$/',$number)) {
            return 'American Express';
        }
        elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',$number)) {
            return 'Diners Club';
        }
        elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/',$number)) {
            return 'Discover';
        }
        elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/',$number)) {
            return 'JCB';
        }
        elseif (preg_match('/^5[1-5][0-9]{14}$/',$number)) {
            return 'MasterCard';
        }
        elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/',$number)) {
            return 'Visa';
        }
        else {
            return 'Unknown';
        }
    }

    public function uploadImage(Request $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $file = $request->file('file');

        if ( $file->getClientOriginalExtension() !== 'jpg' && $file->getClientOriginalExtension() !== 'jpeg' && $file->getClientOriginalExtension() !== 'png' && $file->getClientOriginalExtension() !== 'gif' )
        {
                $this->setStatusCode(402);

                return $this->respondWithError('Only jpeg, png or gif files are allowed');
        }

        $fileName = 'profile-'.$user->id.'.'.$file->getClientOriginalExtension();

        $check = $file->move(env('UPLOAD_PATH').'avatar/', $fileName);

        if ( ! is_a($check, '\Symfony\Component\HttpFoundation\File\File') ) return $this->respondWithError('There was a problem uploading the file.');

        return $this->respond(['success' => true, 'fileName' => $fileName]);

    }
}
