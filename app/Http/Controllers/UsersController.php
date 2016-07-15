<?php

namespace Bsmma\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Bsmma\Http\Requests;
use Illuminate\Support\Facades\DB;

use Bsmma\User;
use Bsmma\UserBalance;
use Bsmma\PaypalEmail;
use Bsmma\MerchantTransaction;
use Bsmma\CreditCardType;
use Bsmma\divStrong\Transformers\ProfileTransformer as ProfileTransformer;
use Bsmma\Http\Requests\ProfileRequest;
use Bsmma\Http\Requests\DepositProfileRequest;

class UsersController extends ApiController
{
    private $stripe;

    public function __construct(
        User $user,
        ProfileTransformer $profileTransformer,
        UserBalance $userBalance,
        PaypalEmail $paypalEmail,
        MerchantTransaction $merchantTransaction,
        CreditCardType $creditCardType
    )
    {
    	$this->user = $user;
        $this->userBalance = $userBalance;
        $this->paypalEmail = $paypalEmail;
    	$this->profileTransformer = $profileTransformer;
        $this->merchantTransaction = $merchantTransaction;
        $this->creditCardType = $creditCardType;
    }

    public function getPlayerName()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return response()->json(['player_name'=>$user->player_name]);
    }

    public function getPlayerBalance()
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $credits = $this->userBalance->where('user_id', $user->id)
                    ->whereIn('transaction_type_id', [2, 4, 5])
                    ->sum('amount');

        $debits = $this->userBalance->where('user_id', $user->id)
                    ->whereIn('transaction_type_id', [1, 3])
                    ->sum('amount');

        return response()->json(['playerBalance' => ($credits - $debits)/100 ]);
    }

    public function profile()
    {
    	$user = \JWTAuth::parseToken()->authenticate();

    	return $this->respond([
            'profile' => [
            	'name' => $user->player_name,
            	'email' => $user->email,
            	'avatar' => 'will be a hash',
            ],
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $userInfo = $this->user->where('id', $user->id)
                        ->first();

        $userInfo->player_name = $request->player_name;

        if ( $request->new_password !== '' && Auth::attempt(['email' => $user->email, 'password' => $request->old_password]) ) {
            $userInfo->password = bcrypt($request->new_password);
        }

        return ($userInfo->save()) ? $this->respond([
            'success' => true,
            'msg' => 'Your profile was updated successfully',
        ]) : $this->respond([
            'success' => false,
            'msg' => 'There was a problem updating your profile'
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
                'name' => $user->player_name,
                'email' => $user->email,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'address' => $user->address1,
                'address2' => $user->address2,
                'city' => $user->city,
                'state' => $user->state,
                'zipcode' => $user->zipcode,
                'merchant' => $user->merchant_id,
                'stripeId' => (is_null($userInfo->stripeDetail)) ? 0 : $userInfo->stripeDetail->stripe_id,
                'ccDigits' => (is_null($userInfo->stripeDetail)) ? '' : $userInfo->stripeDetail->cc_digits,
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

        $userInfo->lastname = $request->lastname;
        $userInfo->firstname = $request->firstname;
        $userInfo->address1 = $request->address;
        $userInfo->address2 = $request->address2;
        $userInfo->city = $request->city;
        $userInfo->state = $request->state;
        $userInfo->zipcode = $request->zipcode;
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

        DB::transaction(function() use ($chargeResponse, $userInfo, $user, $request) {
            $balance = $this->userBalance->create([
                'user_id'             => $user->id,
                'transaction_type_id' => 4,
                'amount'              => $request->deposit,
            ]);

            $this->merchantTransaction->create([
                'merchant_id'      => $request->merchantId,
                'user_balances_id' => $balance->id,
            ]);

            if ( $request->customerState['addCustomer'] ) $this->addStripeCustomer($chargeOutcome, $user);
        });

        return ['success' => true, 'transactionId' => $chargeOutcome['transactionId']];
    }

    private function makeDepositWithPayPal($user, $request)
    {

    }

    private function addPayPalCustomer($request, $userId)
    {
        if ($request->paypal) $this->paypalEmail->create(['user_id' => $userId, 'email' => $request->paypal ]);
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
            'customer' => $userInfo['stripeDetail']['stripe_id'],
            'amount'      => $request->amount,
            'description' => 'BSMMA Funds deposit for '.$userInfo['firstname'].' '.$userInfo['lastname'],
        ];

        return $this->performCharge($chargeInfo);
    }

    private function createCustomerAndCharge($cardInfo, $userInfo, $request)
    {
        $this->stripe->setCardData($cardInfo);
        $tokenResponse = $this->stripe->getToken();

        if ( isset($tokenResponse['failed']) ) return ['success' => false, 'msg' => $tokenResponse['msg']];

        $customerInfo = [
            'source' => $tokenReponse['id'],
            'description' => 'BSMMA Customer Account',
            'metadata' => [
                'name' => $userInfo['firstname'].' '.$userInfo['lastname'],
                'id' => $userInfo['id'],
            ],
            'email' => $userInfo['email'],
        ];

        $customerAccountId = $this->stripe->createCustomer();

        $chargeInfo = [
            'customer' => $customerAccountId,
            'amount'   => $request->amount,
            'description' => 'BSMMA Funds deposit for '.$userInfo['firstname'].' '.$userInfo['lastname']
        ];

        return $this->performCharge($chargeInfo);
    }

    private function updateCustomerAndCharge($cardInfo, $userInfo, $request)
    {
        $this->stripe->setCardData($cardInfo);
        $tokenResponse = $this->stripe->getToken();

        if ( isset($tokenResponse['failed']) ) return ['success' => false, 'msg' => $tokenResponse['msg']];

        $this->stripe->updateCustomer([
            'customerId' => $userInfo['stripeDetail']['stripe_id'],
            'source' => $tokenResponse['id']
        ]);

        return $this->chargeCustomer($userInfo, $request);
    }

    private function addStripeCustomer($responseData, $user)
    {
        $cardType = $this->creditCardType->where('name', $responseData['cardType'])->first();

        $this->stripeDetail->insert([
            'user_id'      => $user->id,
            'stripe_id'    => $responseData['customerId'],
            'cc_digits'    => $responseData['ccDigits'],
            'card_type_id' => $cardType->id,
        ]);
    }

    private function performCharge($chargeInfo)
    {
        $this->stripe->setChargeData($chargeInfo);

        $chargeResponse = $this->stripe->charge();

        if ( isset($chargeResponse['failed']) ) return ['success' => false, 'msg' => $chargeResponse['msg']];

        return ['success' => true, 'transactionId' => $chargeResponse['transactionId'] ];
    }

}
