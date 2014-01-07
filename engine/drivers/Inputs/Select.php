<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Select Input.
 * @date: 25/12/13 22:00
 */

namespace engine\drivers\Inputs;

use engine\drivers\Exceptions\RuleException;
use engine\drivers\Input;

/**
 * Class Select
 * @package engine\drivers\Inputs
 */
class Select extends Input
{

    /**
     * Default error messages.
     */
    const MSG_OPTION_NOT_ALLOWED = "Parameter '%s' in select field '%s' is not allowed.";
    const MSG_INVALID_DATA_TYPE = "Select field '%s' is sending an unexpected value.";

    /**
     * Select Input constructor.
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        // Setting field name
        $this->_fieldName = $fieldName;

        // Initializing valid rules for checkbox inputs
        $this->_validRules = array(
            'availableOptions'
        );

        // Verifying that input fulfills the most basic conditions this kind of input requires.
        try {
            $this->setSelect();
        } catch (RuleException $rEx) {
            $this->setError($rEx);
        }
    }

    /**
     * This function checks and sets the Select input. 
     * In case no options are selected, a RuleException is sent.
     * In case input is not a string a RuleException is sent (This validates that Select is not sent as multiselect).
     * @throws RuleException
     */
    private function setSelect()
    {
        // In case no options are selected, a RuleException is sent.
        if (!isset($_POST[$this->getFieldName()])) {
            $this->setValue('');
            throw new RuleException($this, 'set', '', sprintf(self::MSG_EMPTY_INPUT, $this->getFieldName()));
        }

        // In case multiple options are selected a RuleException is sent.
        $value = $_POST[$this->getFieldName()];
        if(gettype($value) != 'string') {
            $this->setValue('');
            throw new RuleException($this, 'set', '', sprintf(self::MSG_INVALID_DATA_TYPE, $this->getFieldName()));
        }
        
        $this->setValue($value);
    }

    /**
     * List of options that this selector allows.
     * The received parameter must be an array with the allowed values.
     *
     * This rule expects the input to be one of the values in the $options array.
     * @param array $options
     * @throws RuleException
     */
    protected function availableOptions(array $options)
    {
        if (!in_array($this->getValue(), $options)) {
            throw new RuleException ($this, 'availableOptions', $this->getValue(), sprintf(self::MSG_OPTION_NOT_ALLOWED, $this->getValue(), $this->getFieldName()));
        }
    }
}