<?php namespace Bsmma\divStrong\Services;


class Mailchimp
{

	/**
	 * add a users info to a list on mailchimp
	 * @param /Eloquent/Collection  $user  a user collection containing the new users info
	 * @param integer $listId the id of the list to add the user too (default is our general mailing list)
	 */
	public function addUserToList($user, $listId="20f379a44d")
	{
		$jsonData = json_encode(array('email_address'=>$user->email, 'status'=>'pending'));

	    $curl = curl_init();

	    curl_setopt_array($curl, array(
	      CURLOPT_URL => "https://us13.api.mailchimp.com/3.0/lists/$listId/members",
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_CUSTOMREQUEST => "POST",
	      CURLOPT_POSTFIELDS => $jsonData,
	      CURLOPT_HTTPHEADER => array(
	        "authorization: Basic 51225926c0f9f2d3cf02a99711782ce2-us13",
	        "content-type: application/json; charset=utf-8"
	      ),
	      CURLOPT_SSL_VERIFYPEER => false,
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);

	    curl_close($curl);

	    return ($err) ? $err : $response;
	}

}