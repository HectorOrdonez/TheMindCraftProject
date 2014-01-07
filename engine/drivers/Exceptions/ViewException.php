<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * View Exception.
 * @date: 26/12/13 14:30
 */

namespace engine\drivers\Exceptions;

use engine\drivers\Exception;

/**
 * Class ViewException
 * @package engine\drivers\Exceptions
 */
class ViewException extends Exception
{
    public function __construct($message = "", $exceptionType = self::GENERAL_EXCEPTION, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $exceptionType, $code, $previous);
    }
}