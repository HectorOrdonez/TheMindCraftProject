<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Date: 16/06/13 22:37
 */

namespace engine\drivers;

use engine\Exception;

class Validator
{
    /**
     * Name of the Validator.
     * @var string
     */
    protected static $validatorName = 'Undefined';

    /**
     * Expected parameter type. To check if Strict Mode is requested.
     * This property must be overridden by the child class.
     * @var string
     */
    protected static $expectedParameterType = 'Undefined';

    /**
     * List of accepted rules for this Validator.
     * @var array
     */
    protected static $validRules = array();

    /**
     * Validators cannot be instantiated.
     */
    protected function __construct() {}

    /**
     * Validates the parameter following the rules of the instantiated object and rules, if specified.
     * The Strict Mode specifies if the Parameter type must be exactly the type of the Validator.
     * Example:
     *      Validator Int may have a parameter type String, if the parameter can be turned into a Int.
     *      However, if Strict Mode is enabled, this will not be validated.
     *
     * @param mixed $parameter Parameter type is unknown.
     * @param array $rules List of Rules that the parameter must accomplished to be validated.
     * @param boolean $strictMode Optional Parameter. Not all Validator types allows a Strict Mode.
     */
    public static function validate($parameter, $rules = array(), $strictMode = FALSE)
    {
        if ($strictMode === TRUE)
        {
            static::validateParameterType(static::$expectedParameterType, $parameter);
        }

        foreach ($rules as $ruleName => $ruleValue)
        {
            static::validateRule($ruleName);
            static::$ruleName($parameter, $ruleValue);
        }
    }

    /**
     * Validates the parameter type.
     * @param string $type Supposed type of the parameter.
     * @param mixed $parameter Parameter to verify.
     * @throws Exception If the Parameter type is not as expected.
     */
    protected static function validateParameterType ($type, $parameter)
    {
        if (gettype($parameter) != $type)
        {
            throw new Exception ('The type of this parameter should be ' . $type . ' and it is ' . gettype($parameter) . ' instead.');
        }
    }

    /**
     * Validates specified Rule.
     * @param string $rule Rule to validate.
     * @throws Exception If the rule is not valid in this validator.
     */
    protected static function validateRule($rule)
    {
        if (!in_array($rule, static::$validRules))
        {
            throw new Exception ('Requested rule ' . $rule . ' is not accepted for the Validator ' . static::$validatorName);
        }
    }
}