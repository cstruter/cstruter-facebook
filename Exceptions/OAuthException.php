<?php

namespace CSTruter\Facebook\Exceptions;

/**
 * Facebook Auth Exception
 *
 * Exception that should be thrown if Facebook Authentication fails
 *
 * @package CSTruter\Service
 * @author Christoff Truter <christoff@cstruter.com>
 * @copyright 2005-2015 CS Truter
 * @version 0.1.0
*/
class OAuthException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

?>