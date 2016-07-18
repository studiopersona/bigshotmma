<?php
namespace Bsmma\divStrong\Merchants;

use Bsmma\Contracts\MerchantContract;
use Validator;

class PayPalMerchant implements MerchantContract {

	public $chargeData;
	public $chargeUrl;

	public $returnUrl;
	public $businessEmail;

	public function __construct()
	{
		$this->chargeData = [];
		$this->chargeUrl = env('PAYPAL_CHARGE_URL');
		$this->returnUrl = env('PAYPAL_RETURN_URL');
		$this->businessEmail = env('PAYPAL_BUSINESS_EMAIL');
	}

	public function setChargeData(array $input)
	{
		dump($input);
		$this->chargeData = [
			'cmd' => $input['cmd'],
			'email' => $input['paypalEmail'],
			'business' => $this->businessEmail,
			'return' => $this->returnUrl,
			'amount' => $input['amount']
		];
	}

	public function charge()
	{
		if (empty($this->chargeData)) return [
				'failed' => true,
				'message' => 'Charge data was not set'
			];

		$charge = $this->executeCurl();

		return [
			'success' => true,
			'message' => 'Your desposit has been applied to your account',
		];
	}

	protected function executeCurl()
	{
		$ch = curl_init($this->chargeUrl);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->chargeData));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_CAINFO, storage_path('app/cacert.pem'));

		$response = curl_exec($ch);

		dump($response);

		curl_close($ch);

		return $response;
	}
}