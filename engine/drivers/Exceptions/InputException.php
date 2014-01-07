<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Exception triggered by an Input because of an unexpected behavior.
 * @date: 13/12/13 16:00
 */

namespace engine\drivers\Exceptions;

use engine\drivers\Exception;

/**
 * Class InputException
 * @package engine\drivers\Exceptions
 */
class InputException extends Exception
{
    /**
     * Input name that did not pass validation.
     * @var
     */
    protected $_fieldName;

    /**
     * InputException constructor.
     * @param string $fieldName Input name that throws exception
     * @param string $message Default message that explains the exception.
     * @param Exception|int $exceptionType Exception level.
     * @param int $code Exception code.
     * @param Exception $previous
     */
    public function __construct($fieldName, $message = "", $exceptionType = self::GENERAL_EXCEPTION, $code = 0, Exception $previous = null)
    {
        $this->_fieldName = $fieldName;

        parent::__construct($message, $exceptionType, $code, $previous);
    }

    /**
     * Returns the Input name.
     * @return string
     */
    public function getInputName()
    {
        return $this->_fieldName;
    }
}