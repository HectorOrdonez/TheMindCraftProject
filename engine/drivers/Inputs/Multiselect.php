<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Multiselect Input.
 * Date: 25/12/13 23:00
 */

namespace engine\drivers\Inputs;

use engine\drivers\Exceptions\RuleException;
use engine\drivers\Input;

/**
 * Class Multiselect
 * @package engine\drivers\Inputs
 */
class Multiselect extends Input
{
    /**
     * Default error messages.
     */
    const MSG_OPTION_NOT_ALLOWED = "Parameter '%s' in multiselect field '%s' is not allowed.";
    const MSG_INVALID_DATA_TYPE = "Multiselect field '%s' is sending an unexpected value.";
    const MSG_MIN_SELECTION_NOT_REACHED = "Multiselect field '%s' needs at least %d selected options, %d sent.";
    const MSG_MAX_SELECTION_EXCEEDED = "Multiselect field '%s' can not have more than %d selected options, %d sent.";

    /**
     * Multiselect Input constructor.
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        // Setting field name
        $this->_fieldName = $fieldName;

        // Initializing valid rules for checkbox inputs
        $this->_validRules = array(
            'availableOptions',
            'minOptions',
            'maxOptions'
        );

        // Verifying that input fulfills the most basic conditions this kind of input requires.
        try {
            $this->setMultiselect();
        } catch (RuleException $rEx) {
            $this->setError($rEx);
        }
    }

    /**
     * This function checks and sets the Multiselect Input.
     * In case no options are selected, a RuleException is sent.
     * In case input is not an array, a RuleException is sent (This validates that Multiselect is not sent as Select).
     * @throws RuleException
     */
    private function setMultiselect()
    {
        // In case no options are selected, Multiselect value is an empty array.
        if (!isset($_POST[$this->getFieldName()])) {
            $this->setValue(array());
            return;
        }

        // In case input is not an array a RuleException is sent.
        $value = $_POST[$this->getFieldName()];
        if (gettype($value) != 'array') {
            $this->setValue(array());
            throw new RuleException($this, 'set', '', sprintf(self::MSG_INVALID_DATA_TYPE, $this->getFieldName()));
        }

        $this->setValue($value);
    }

    /**
     * List of options that this multiselect allows.
     * The received parameter must be an array with the allowed values.
     *
     * This rule expects all values in input array to be one of the values in the $options array.
     * @param array $options
     * @throws RuleException
     */
    protected function availableOptions(array $options)
    {
        foreach ($this->getValue() as $value) {
            if (!in_array($value, $options)) {
                throw new RuleException ($this, 'availableOptions', $value, sprintf(self::MSG_OPTION_NOT_ALLOWED, $value, $this->getFieldName()));
            }
        }
    }

    /**
     * Minimum amount of selected options.
     * @param int $min
     * @throws RuleException
     */
    protected function minOptions($min)
    {
        if ($min > sizeof($this->getValue())) {
            throw new RuleException ($this, 'minOptions', sizeof($this->getValue()), sprintf(self::MSG_MIN_SELECTION_NOT_REACHED, $this->getFieldName(), $min, sizeof($this->getValue())));
        }
    }

    /**
     * Maximum amount of selected options.
     * @param int $max
     * @throws RuleException
     */
    protected function maxOptions($max)
    {
        if ($max < sizeof($this->getValue())) {
            throw new RuleException ($this, 'maxOptions', sizeof($this->getValue()), sprintf(self::MSG_MAX_SELECTION_EXCEEDED, $this->getFieldName(), $max, sizeof($this->getValue())));
        }
    }
}