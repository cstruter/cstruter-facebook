<?php

namespace CSTruter\Facebook;

use CSTruter\Facebook\Exceptions\OAuthException;

/**
 * Facebook Api Class
 *
 * This class consumes the Facebook Web Service(s) and provides easy access
 * to its methods (I only implemented the methods I use / needed).
 *
 * @package CSTruter\Service
 * @author Christoff Truter <christoff@cstruter.com>
 * @copyright 2005-2015 CS Truter
 * @version 0.1.0
*/
class FacebookApi
{
	private $appId;
	private $token;
	private $secret;
	
	public function __construct($appId, $secret, $token = NULL) {
		$this->appId = $appId;
		$this->secret = $secret;
		$this->token = $token;
	}
	
	public function getUser($user_id = 'me') {
		if (empty($this->token)) {
			throw new \Exception('Token Required');
		}
		$request = new FacebookRequest('https://graph.facebook.com/'.$user_id.'/?access_token='.$this->token);
		return $request->getResponse();
	}
	
	public function validateSignedRequest($code) {
		$request = new FacebookRequest('https://graph.facebook.com/oauth/access_token?client_id='.$this->appId.'&redirect_uri=&client_secret='.$this->secret.'&code='.$code);
		return $request->getResponse();
	}

	public function parseSignedRequest($signed_request) {
		$segments = explode('.', $signed_request); 
		
		if (count($segments) > 1) {
			list($encoded_signature, $payload) = $segments;
		} else {
			throw new OAuthException('Bad signed request');
		}
		
		$json = json_decode($this->base64_url_decode($payload), true);
		
		if (empty($json)) {
			throw new OAuthException('Bad payload');
		}
		
		$signature = $this->base64_url_decode($encoded_signature);
		$expected_signature = hash_hmac('sha256', $payload, $this->secret, $raw = true);
		
		if ($signature !== $expected_signature) {
			throw new OAuthException('Bad signed JSON signature');
		}
		
		return  $json;
	}
	
	private function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}	
}

?>