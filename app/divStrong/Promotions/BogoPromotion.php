<?php
namespace Bsmma\divStrong\Promotions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

use Bsmma\BogoPromo;
use Bsmma\BogoPromosToUser;

class BogoPromotion
{
	private $bogoPromo;
	private $bogoPromoToUser;


	public function __construct(BogoPromo $bogoPromo, BogoPromosToUser $bogoPromoToUser)
	{
		$this->bogoPromo = $bogoPromo;
		$this->bogoPromoToUser = $bogoPromoToUser;
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

    	$promoUserId = $this->enterPlayerPromo($codeCheck, $user->id);

        return $this->prepResponseData($codeCheck, $promoUserId);
    }

    public function checkForActiveCode($userId)
    {
        $codeCheck = $this->bogoPromosToUser->where('user_id', $userId)
                        ->with('bogoPromo')
                        ->where('is_complete', 0)
                        ->whereNotNull('entered_code_on')
                        ->first();

        if ( is_null($codeCheck) ) return [
            'promoUserId' => 0,
            'id' => 0,
            'code' => '',
            'status' => [],
        ];

        return $this->prepResponseData($codeCheck->bogoPromo, $codeCheck->id);
    }

    public function backupStage($request)
    {
        $userPromo = $this->bogoPromoToUser->where('id', $request->userPromoId)->first();

        if ( $userPromo->entered_free_contest_on !== '0000-00-00 00:00:00' ) {
            $userPromo->entered_free_contest_on = NULL;
        } else {
            $userPromo->entered_paid_contest_on = NULL;
        }

        $userPromo->save();
    }

    public function moveToStage2($request, $userId)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromoToUser->where('id', $request->promoUserId)
                ->update(['paid_contest_id', $request->paid_contest_id, 'entered_paid_contest_on' => date('Y-m-d H:i:s')]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 2', ['user_id' => $userId, 'promoUserId' => $request->promoUserId, 'date' => date()]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    public function moveToStage3($request)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromoToUser->where('id', $request->promoUserId)
                ->update(['completed_paid_contest_on' => date('Y-m-d H:i:s')]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 3', ['user_id' => $userId, 'promoUserId' => $request->promoUserId, 'date' => date()]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    public function moveToStage4($request)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromoToUser->where('id', $request->promoUserId)
                ->update(['free_contest_id', $request->free_contest_id, 'entered_free_contest_on' => date('Y-m-d H:i:s')]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 4', ['user_id' => $userId, 'promoUserId' => $request->promoUserId, 'date' => date()]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    public function moveToStage5($request)
    {
        DB::beginTransaction();

        try {
            $this->bogoPromoToUser->where('id', $request->promoUserId)
                ->update(['is_complete' => 1]);
        }
        catch(\Exception $e) {
            DB::rollback();

            Log::error('Couldn\'t update promo to stage 5', ['user_id' => $userId, 'promoUserId' => $request->promoUserId, 'date' => date()]);

            // create a adminNotifier service that will alert admins to the error
        }

        DB::commit();
    }

    protected function enterPlayerPromo($promo, $userId)
    {
        $promoUserId = $this->bogoPromoToUser->create([
            'bogo_promo_id' => $promo->id,
            'user_id' => $userId,
            'entered_code_on' => date('Y-m-d H:i:s'),
        ]);

        return $promoUserId;
    }

    protected function prepResponseData($data, $promoUserId)
    {
        return [
            'valid' => true,
            'promo' => [
                'promoUserId' => $promoUserId,
                'id' => $data->id,
                'code' => $data->code,
                'status' => determineBogoStatus($data),
            ],
        ];
    }
}
