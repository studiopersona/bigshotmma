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

    	$this->enterPlayerPromo($codeCheck, $user->id);

    	return [
    		'valid' => true,
    		'promo' => [
    			'id' => $codeCheck->id,
    			'code' => $codeCheck->code,
    			'status' => determineBogoStatus($codeCheck),
    		],
    	];
    }

    protected function enterPlayerPromo($promo, $userId)
    {
    	$this->bogoPromoToUser->create([
    		'bogo_promo_id' => $promo->id,
    		'user_id' => $userId,
    		'entered_code_on' => date('Y-m-d H:i:s'),
    	]);
    }
}
