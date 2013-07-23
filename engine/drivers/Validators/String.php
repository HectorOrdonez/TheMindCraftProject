<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Date: 16/06/13 22:36
 */

namespace engine\drivers\validators;

use engine\drivers\Validator;
use engine\Exception;

class String extends Validator
{
    /**
     * Name of the Validator.
     * @var string
     */
    protected static $validatorName = 'String';

    /**
     * Expected parameter type. To check if Strict Mode is requested.
     * @var string
     */
    protected static $expectedParameterType = 'String';

    /**
     * List of accepted rules for this Validator.
     * @var array
     */
    protected static $validRules = array(
        'minLength',
        'maxLength'
    );

    /**
     * Minimum length of this string.
     * @param string $parameter Parameter being validated.
     * @param int $value Minimum length of the string
     * @throws Exception triggered if string length is lower than expected.
     */
    protected static function minLength ($parameter, $value)
    {
        if (strlen($parameter) < $value)
        {
            throw new Exception ('Parameter [' . $parameter .'] does not have the minimum length of ' . $value .'.');
        }
    }

    /**
     * Maximum length of this string.
     * @param string $parameter Parameter being validated.
     * @param int $value Maximum length of the string
     * @throws Exception triggered if string length is higher than expected.
     */
    protected static function maxLength ($parameter, $value)
    {
        if (strlen($parameter) > $value)
        {
            throw new Exception ('Parameter [' . $parameter .'] exceeds the maximum length of ' . $value .'.');
        }
    }
}