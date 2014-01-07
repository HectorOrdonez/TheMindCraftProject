<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Abstract Input, extended by all Input Objects such as Text and Number.
 * The purpose of these objects is to help controllers get POST data from User when sent.
 * @date: 13/12/13 16:00
 */

namespace engine\drivers;

use engine\drivers\Exceptions\InputException as InputException;
use engine\drivers\Exceptions\RuleException;

/**
 * Class Input
 * @package engine\drivers
 */
abstract class Input
{
    /**
     * Default error messages.
     */
    const MSG_EMPTY_INPUT = 'The input %s is empty.';
    const MSG_INVALID_RULE = 'The input type %s does not allow the rule %s.';
    const MSG_INVALID_VALUE = 'Can not provide the value of the input $s because it did not pass validation.';

    /**
     * Field name related to this input.
     * @var string
     */
    protected $_fieldName = '';

    /**
     * Accepted rules for this input.
     * @var array
     */
    protected $_validRules = array();

    /**
     * Requested rules to verify when validating this input.
     * @var array
     */
    protected $_requestedRules = array();

    /**
     * The input value.
     * @var null
     */
    protected $_value = null;

    /**
     * False by default. When Input is validated, the RuleExceptions triggered are stored in this parameter.
     * @var bool | RuleException
     */
    protected $_error = false;

    /**
     * Input constructors will always require a field name string and, optionally, a required rules array.
     * @param string $fieldName
     */
    abstract public function __construct($fieldName);

    /**
     * @throws Exceptions\RuleException|\Exception
     */
    public function validate()
    {
        // In case there is already an error, Input failed to initialize.
        if (false !== $this->getError()) {
            throw $this->getError();
        }

        foreach ($this->_requestedRules as $ruleName => $ruleValue) {
            try {
                $this->{$ruleName}($ruleValue);
            } catch (RuleException $rEx) {
                $this->setError($rEx);
                throw $rEx;
            }
        }
    }

    /**
     * Gets this input fieldName.
     * @return string
     */
    public function getFieldName()
    {
        return $this->_fieldName;
    }

    /**
     * Adds a rule to the rules list to verify when validating this input.
     * The rule has to be included in the rules list accepted for this specific input type.
     *
     * Notice that this method does not throw an InputException. That is because if an Input is requested a rule not
     * related to it, the problem is beyond the Input - something out there thinks this input is something it ain't!
     *
     * @param string $rule Rule.
     * @param null $value Optional value that some rules needs to work.
     * @return Input $this
     * @throws InputException
     */
    public function addRule($rule, $value = null)
    {
        if (!in_array($rule, $this->_validRules)) {
            throw new InputException($this->getFieldName(), sprintf(self::MSG_INVALID_RULE, get_class($this), $rule));
        }
        $this->_requestedRules[$rule] = $value;

        return $this;
    }

    /**
     * Delivers this input value.
     * @return null
     * @throws Exceptions\InputException
     */
    public function getValue()
    {
        if (false !== $this->getError()) {
            throw new InputException($this->getFieldName(), sprintf(self::MSG_INVALID_VALUE, $this->getFieldName()));
        }
        return $this->_value;
    }

    /**
     * Sets the input value.
     * @param $value
     */
    protected function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * Sets an input error when required.
     * @param RuleException $rEx
     */
    protected function setError(RuleException $rEx)
    {
        $this->_error = $rEx;
    }

    /**
     * Gets the input error. Error can be a RuleException or false, which means Input passed validation.
     * @return bool|RuleException
     */
    public function getError()
    {
        return $this->_error;
    }
}