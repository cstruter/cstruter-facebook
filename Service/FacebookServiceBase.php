<?php

namespace CSTruter\Facebook\Service;

use CSTruter\Facebook\FacebookApi,
	CSTruter\Service\ServiceBase,
	CSTruter\Service\Interfaces\ISecureService,
	CSTruter\Facebook\Exceptions\OAuthException;

/**
 * Facebook Service Base Class 
 *
 * Base class for creating Web Services that validate against Facebook.
 *
 * @package CSTruter\Service
 * @author Christoff Truter <christoff@cstruter.com>
 * @copyright 2005-2015 CS Truter
 * @version 0.1.0
*/
abstract class FacebookServiceBase extends ServiceBase implements ISecureService
{
	private $api;
	
	public function __construct($appId, $secret) { 
		$this->api = new FacebookApi($appId, $secret);
		parent::__construct(); 
	}
	
	protected function getUserId() {
		return $_SESSION['id'];
	}
	
	public function isValidated() {
		return (isset($_SESSION['id']));
	}
	
	public function validate($signed_request = NULL)
	{
		try
		{
			$json = $this->api->parseSignedRequest($signed_request);
			if ((!isset($_SESSION['signed_request'])) || ($_SESSION['signed_request'] != $signed_request)) 
			{
				$this->api->validateSignedRequest($json['code']);
				$_SESSION['signed_request'] = $signed_request;	
			}
			$_SESSION['id'] = $json['user_id'];
			return true;
		}
		catch(OAuthException $ex)
		{
			$_SESSION = [];
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			session_destroy();
			return false;
		}
	}
}

?>