<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Date: 16/06/13 22:00
 *
 * @todo Create a special Exception type that allows logic to filter the exceptions triggered by the Validator.
 */

namespace engine;

use engine\drivers\validators as Validators;

class Validator
{
    // This is not an instantiable class.
    private function __construct() {}

    /**
     * Calls to Validator are in the format: Validator::$type($parameter, $rulesArray, $strictMode);
     *
     * @param string $type Name of Validator Type
     * @param array $args List of arguments;
     *      0 => $parameter
     *      1 => $rules
     *      2 => $strictMode
     * @throws Exception
     */
    public static function __callStatic($type, $args)
    {
        $parameter = $args[0];
        $rules = (isset($args[1])) ? $args[1] : NULL;
        $strictMode = (isset($args[2])) ? $args[2] : NULL;

        switch ($type)
        {
            case 'String':
                Validators\String::validate($parameter, $rules, $strictMode);
                break;
            case 'Int':
                Validators\Int::validate($parameter, $rules, $strictMode);
                break;
            case 'Enum':
                Validators\Enum::validate($parameter, $rules, $strictMode);
                break;
            default:
                throw new Exception ('Requested Validator type "' . $type . '" does not exist.');
        }
    }
}