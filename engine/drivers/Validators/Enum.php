<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Date: 17/06/13 13:49
 */

namespace engine\drivers\validators;

use engine\drivers\Validator;
use engine\Exception;

class Enum extends Validator
{
    /**
     * Name of the Validator.
     * @var string
     */
    protected static $validatorName = 'Enum';

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
        'availableOptions'
    );

    /**
     * Checks if the parameter is one of the available options.
     * @param string $parameter Parameter being validated.
     * @param array $options List of possible options
     * @throws Exception triggered if parameter is not one of the possible options.
     */
    protected static function availableOptions ($parameter, $options)
    {
        if (!in_array($parameter, $options))
        {
            throw new Exception ('Parameter [' . $parameter .'] is not allowed for this field.');
        }
    }
}