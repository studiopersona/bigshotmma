<?php namespace Invoisa\Merchants;

use Invoisa\Contracts\MerchantContract;
use Invoisa\Contracts\CurrencyContract as Currency;
use Invoisa\Contracts\UserSettingContract as Settings;
use Validator;
use Config;

use Stripe;
use Stripe_Charge;
use Stripe_CardError;
use Stripe_InvalidRequestError;
use Stripe_AuthenticationError;
use Stripe_Error;

class StripeMerchant implements MerchantContract {

	// the data required to process a charge
	public $data;

	private $api_key;

	public function __construct()
	{

	}

	public function charge()
	{
		// We use our platforms secret key here to, this along with the tenants stripe_user_id
		// is all we need to make a charge directly to the tenants account.
		//
		// Set your secret key: remember to change this to your live secret key in production
		// See your keys here https://manage.stripe.com/account
		Stripe::setApiKey($this->api_key); // test api key goes here
		// Get the credit card details submitted by the form
		$token = $this->data['stripeToken'];
		// Create the charge on Stripe's servers - this will charge the user's card
		try {
			$charge = Stripe_Charge::create(array(
			  'amount' => $this->data['amount'] * 100, // amount in cents, again
			  'currency' => $this->data['currency_code'], // this should come from the users currency setting
			  'source' => $token,
			  'description' => 'Payment for invoice number '.$this->data['invoice_number'].' form '.$this->data['client_company']
			), array('stripe_account' => $this->data['stripe_account']));
		} catch(Stripe_CardError $e) {
		  	// The card has been declined
		  	$body = $e->getJsonBody();
	  		$err  = $body['error'];
	  		return array('failed'=>true, 'msg'=>$err);

		} catch (Stripe_InvalidRequestError $e) {
		 	return array('failed'=>true, 'msg'=>'Invalid parameters were supplied to Stripe\'s API', 'error'=>$e);

		} catch (Stripe_AuthenticationError $e) {
		  	return array('failed'=>true, 'msg'=>'Authentication with Stripe\'s API failed (maybe you changed API keys recently)');

		} catch (Stripe_ApiConnectionError $e) {
		  	return array('failed'=>true, 'msg'=>'Network communication with Stripe failed');

		} catch (Stripe_Error $e) {
		  	return array('failed'=>true, 'msg'=>'Display a very generic error to the user, and maybe send yourself an email');

		} catch (Exception $e) {
		  	return array('failed'=>true, 'msg'=>'Something else happened, completely unrelated to Stripe');

		}

		if (isset($charge)) {
			return ['transactionId' => $charge->id];
		}
	}

	public function setData(array $input, $invoice, array $settings)
	{
		$this->api_key = $settings['access_token'];

		$this->data = [
			'stripeToken'    => $input['stripeToken'],
			'amount'         => $invoice->total,
			'currency_code'  => $currency->code,
			'invoice_number' => $invoice->number,
			'client_company' => $invoice->client->company,
			'stripe_account' =>	$settings['stripe_user_id']
		];
	}
}