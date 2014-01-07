<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Form Exception.
 * @date: 13/12/13 16:00
 */

namespace engine\drivers\Exceptions;

use engine\drivers\Exception;

/**
 * Class FormException
 * @package engine\drivers\Exceptions
 */
class FormException extends Exception
{
    public function __construct($message = "", $exceptionType = self::GENERAL_EXCEPTION, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $exceptionType, $code, $previous);
    }
}