<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * @date: 13/12/13 15:30
 */

namespace engine;

use engine\drivers\Exception;
use engine\drivers\Inputs as Inputs;
use engine\drivers\Input as InputObj;

/**
 * Class Input
 * @package engine
 */
class Input
{
    // This is not an instantiable class.
    private function __construct()
    {
    }

    /**
     * Calls to Input are in the format: Input::$type($fieldName);
     *
     * E.g Input::text('username') will return an object Text, which extends the class Input, with 'username' as parameter fieldName.
     *
     * @param $type
     * @param $fieldName
     * @return InputObj
     * @throws drivers\Exception
     */
    public static function build($type, $fieldName)
    {
        switch ($type) {
            case 'Text':
                return new Inputs\Text($fieldName);
                break;
            case 'Number':
                return new Inputs\Number($fieldName);
                break;
            case 'Mail':
                return new Inputs\Mail($fieldName);
                break;
            case 'Checkbox':
                return new Inputs\Checkbox($fieldName);
                break;
            case 'Select':
                return new Inputs\Select($fieldName);
                break;
            case 'Multiselect':
                return new Inputs\Multiselect($fieldName);
                break;
            case 'Date':
                return new Inputs\Date($fieldName);
                break;
            default:
                throw new Exception ("Requested Input type {$type} does not exist.", Exception::FATAL_EXCEPTION);
        }
    }
}