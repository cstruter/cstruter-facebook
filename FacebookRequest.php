<?php

namespace CSTruter\Facebook;

use CSTruter\Misc\Request,
	CSTruter\Facebook\Exceptions\OAuthException;

/**
 * Facebook Web Request
 *
 * The class represents a request made to the Facebook Web Service(s), 
 * it is basically an adapter to the Misc Request Class.
 *
 * @package CSTruter\Service
 * @author Christoff Truter <christoff@cstruter.com>
 * @copyright 2005-2015 CS Truter
 * @version 0.1.0
*/
class FacebookRequest
{
	private $request;
	
	public function __construct($url) {
		$this->request = new Request($url, true);
	}
	
	public function getResponse()
	{
		$response = $this->request->getResponse();
		$jsonResponse = json_decode($response, true);
		if (isset($jsonResponse['error'])) {
			throw new OAuthException($jsonResponse['error']['message'], $jsonResponse['error']['code']);
		}
		return $jsonResponse;
	}
}

?>