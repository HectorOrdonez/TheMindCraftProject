<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Exception triggered by an Input because of a non-passed rule.
 * @date: 18/12/13 23:00
 */

namespace engine\drivers\Exceptions;

use engine\drivers\Exception;
use engine\drivers\Input;

/**
 * Class RuleException
 * @package engine\drivers\Exceptions
 */
class RuleException extends Exception
{
    /**
     * Input object that generated this Exception.
     * @var Input
     */
    protected $_input;
    /**
     * Rule name that did not pass validation.
     * @var
     */
    protected $_ruleName;

    /**
     * Input Value that could not pass the validation.
     * @var
     */
    protected $_value;

    /**
     * RuleException constructor.
     * @param Input $input Input name that throws exception
     * @param string $ruleName The violated rule.
     * @param null $value The incorrect value.
     * @param string $message Default message that explains the exception.
     * @param Exception|int $exceptionType Exception level.
     * @param int $code Exception code.
     * @param Exception $previous
     */
    public function __construct($input, $ruleName, $value, $message = "", $exceptionType = self::GENERAL_EXCEPTION, $code = 0, Exception $previous = null)
    {
        $this->_input = $input;
        $this->_ruleName = $ruleName;
        $this->_value = $value;

        parent::__construct($message, $exceptionType, $code, $previous);
    }

    /**
     * Returns the violated rule name.
     * @return string
     */
    public function getViolatedRule()
    {
        return $this->_ruleName;
    }

    /**
     * Returns the Input.
     * @return Input
     */
    public function getInput()
    {
        return $this->_input;
    }

    /**
     * Returns the value that broke the rule.
     * @return null
     */
    public function getIncorrectValue()
    {
        return $this->_value;
    }
}