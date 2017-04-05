<?php
namespace Bsmma\divStrong\Promotions;

use Bsmma\BogoPromo;
use Bsmma\BogoPromosToUser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BogoPromotion
{
	private $bogoPromo;
	private $bogoPromosToUser;


	public function __construct(BogoPromo $bogoPromo, BogoPromosToUser $bogoPromosToUser)
	{
		$this->bogoPromo = $bogoPromo;
		$this->bogoPromosToUser = $bogoPromosToUser;
	}

	public function validateCode(Request $request, $user)
    {
    	$codeCheck = $this->bogoPromo->where('code', $request->promoCode)
    					->where('is_active', 1)
    					->get()
    					->filter(function($bogo) {
    						return ( $bogo->expiration_date === '0000-00-00 00:00:00' || ($bogo->expiration_date > date('Y-m-d H:i:s')) );
    					});

    	if ( $codeCheck->isEmpty() ) return ['error' => 'The code entered is not valid'];

    	$codeCheck = $codeCheck->shift();

        if ( $this->hasCodeBeenUsedByPlayer($codeCheck, $user->id) ) return ['error' => 'You have already redeemed this code.'];

    	$promoUserId = $this->enterPlayerPromo($codeCheck, $user->id);

        return [
            'valid' => true,
            'promo' => [
                'promoUserId' => $user->id,
                'id' => $codeCheck->id,
                'code' => $codeCheck->code,
                'status' => [
                    'stage' => 1,
                    'display' => 'Enter an eligible contest ($'.implode(', $', explode(',', $codeCheck->valid_entry_fees)).')',
                ],
                'validEntryFees' => explode(',', $codeCheck->valid_entry_fees),
                'paidContestEntryFee' => $codeCheck->paid_contest_entry_fee,
            ],
        ];
    }

    public function checkForActiveCode($userId)
    {
        $codeCheck = $this->bogoPromosToUser->where('user_id', $userId)
                        ->with('bogoPromo')
                        ->where('is_complete', 0)
                        ->whereNotNull('entered_code_on')
                        ->first();

        if ( is_null($codeCheck) ) return [
            'valid' => false,
            'promo' => [
                'promoUserId' => 0,
                'id' => 0,
                'code' => '',
                'status' => [],
                'validEntryFees' => [],
                'paidContestEntryFee' => -1,
            ],
        ];

        return $this->prepResponseData($codeCheck, $codeCheck->id);
    }

    public function backupStage($request)
    {
        $userPromo = $this->bogoPromosToUser->where('id', $request->promoUserId)->where('is_complete', 0)->first();

        if ( is_null($userPromo->free_contest_id) ) {
            $userPromo->entered_paid_contest_on = NULL;
            $userPromo->paid_contest_id = NULL;
        } else {
            $userPromo->entered_free_contest_on = NULL;
            $userPromo->free_contest_id = NULL;
        }

        $userPromo->save();
    }

    public function moveToStage2(Request $request, \Bsmma\User $user)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromosToUser->where('id', $request->promoUserId)
                ->update(['paid_contest_id'=> $request->paid_contest_id, 'entered_paid_contest_on' => date('Y-m-d H:i:s'), 'paid_contest_entry_fee' => $request->entryFee]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 2', ['user_id' => $user->id, 'promoUserId' => $request->promoUserId, 'date' => date('Y-m-d H:i:s'), 'exception' => $e]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    public function moveToStage3(Request $request, \Bsmma\User $user)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromosToUser->where('id', $request->promoUserId)
                ->update(['completed_paid_contest_on' => date('Y-m-d H:i:s')]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 3', ['user_id' => $user->id, 'promoUserId' => $request->promoUserId, 'date' => date(), 'exception' => $e]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    public function moveToStage4(Request $request, \Bsmma\User $user)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromosToUser->where('id', $request->promoUserId)
                ->update(['free_contest_id' => $request->free_contest_id, 'entered_free_contest_on' => date('Y-m-d H:i:s')]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 4', ['user_id' => $user->id, 'promoUserId' => $request->promoUserId, 'date' => date(), 'exception' => $e]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    public function moveToStage5(Request $request, \Bsmma\User $user)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromosToUser->where('id', $request->promoUserId)
                ->update(['is_complete' => 1]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 5', ['user_id' => $user->id, 'promoUserId' => $request->promoUserId, 'date' => date(), 'exception' => $e]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    protected function enterPlayerPromo($promo, $userId)
    {
        $promoUserId = $this->bogoPromosToUser->create([
            'bogo_promo_id' => $promo->id,
            'user_id' => $userId,
            'entered_code_on' => date('Y-m-d H:i:s'),
        ]);

        return $promoUserId;
    }

    protected function hasCodeBeenUsedByPlayer($codeData, $userId)
    {
        return !$this->bogoPromosToUser->where('user_id', $userId)
                ->where('bogo_promo_id', $codeData->id)
                ->get()->isEmpty();
    }

    protected function prepResponseData($data, $promoUserId)
    {
        return [
            'valid' => true,
            'promo' => [
                'promoUserId' => $promoUserId,
                'id' => $data->id,
                'code' => $data->bogoPromo->code,
                'status' => determineBogoStatus($data, explode(',',$data->bogoPromo->valid_entry_fees)),
                'validEntryFees' => explode(',', $data->bogoPromo->valid_entry_fees),
                'paidContestEntryFee' => $data->paid_contest_entry_fee,
            ],
        ];
    }
}
