<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Checkbox Input.
 * Date: 25/12/13 21:30
 */

namespace engine\drivers\Inputs;

use engine\drivers\Exceptions\RuleException;
use engine\drivers\Input;

/**
 * Class Checkbox
 * @package engine\drivers\Inputs
 */
class Checkbox extends Input
{
    /**
     * Constants for checked and unchecked values.
     */
    const CHECKED = true;
    const UNCHECKED = false;

    /**
     * Accepted Checkbox value.
     */
    const ENABLED_CHECKBOX = 'on';

    /**
     * Default error messages.
     */
    const MSG_UNEXPECTED_CHECKBOX_VALUE = "Checkbox field '%s' is sending an unexpected value: '%s'.";

    /**
     * Checkbox Input constructor.
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        // Setting field name
        $this->_fieldName = $fieldName;

        // Initializing valid rules for checkbox inputs
        $this->_validRules = array(
        );

        // Verifying that input fulfills the most basic conditions this kind of input requires.
        try {
            $this->setCheckbox();
        } catch (RuleException $rEx) {
            $this->setError($rEx);
        }
    }

    /**
     * This function checks and sets the Checkbox input.
     * If Checkbox is send as 'on', the input value is true. If it s not sent, is false.
     * In case the input contains a weird value, a Rule Exception is sent.
     * @throws RuleException
     */
    private function setCheckbox()
    {
        // In case field is not sent, checkbox is considered unchecked.
        if (!isset($_POST[$this->getFieldName()])) {
            $this->setValue(self::UNCHECKED);
            return;
        }

        // In case field is sent, value is verified - checked checkboxes might contain only the value 'on'.
        $value = $_POST[$this->getFieldName()];
        if (self::ENABLED_CHECKBOX != $value) {
            $this->setValue('');
            throw new RuleException($this, 'set', $value, sprintf(self::MSG_UNEXPECTED_CHECKBOX_VALUE, $this->getFieldName(), $value));
        }
        
        $this->setValue(self::CHECKED);
    }
}