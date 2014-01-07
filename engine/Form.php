<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 *
 * This class manages the collection of Post data, allowing validations.
 * @date: 16/06/13 21:45
 * @updated: 13/12/13 15:30 Refactored the Form to work with Input objects.
 */

namespace engine;

use engine\drivers\Exceptions\FormException;
use engine\drivers\Exceptions\InputException;
use engine\drivers\Exceptions\RuleException;
use engine\drivers\Input as Input;

/**
 * Class Form
 * @package engine
 */
class Form
{
    /**
     * List of inputs. Contain Input objects.
     * @var Input[]
     */
    private $_inputs;

    /**
     * @var Input[]
     */
    private $_errorInputs;

    /**
     * Form constructor.
     */
    public function __construct()
    {
    }

    /**
     * Adds an Input to this Form input list. The position in the list is the input field name.
     * @param Input $input
     */
    public function addInput(Input $input)
    {
        $this->_inputs[$input->getFieldName()] = $input;
    }

    /**
     * Gets an Input from this Form input list. The input field name is required as the inputs are indexed by it.
     * @param $fieldName
     * @return Input
     * @throws FormException
     */
    public function getInput($fieldName)
    {
        if (!isset($this->_inputs[$fieldName])) {
            throw new FormException("Field {$fieldName} not found in Form input list.");
        }
        return $this->_inputs[$fieldName];
    }

    /**
     * Returns either an array of Error Inputs or FALSE, if there are no error inputs.
     * @return bool|Input[]
     */
    public function getValidationErrors()
    {
        foreach ($this->_inputs as $input) {
            try {
                $input->validate();
            } catch (RuleException $rEx) {
                $this->_errorInputs[$input->getFieldName()] = $input;
            }
        }
        if (sizeof($this->_errorInputs) > 0) {
            return $this->_errorInputs;
        } else {
            return FALSE;
        }
    }
}