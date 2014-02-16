<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Date Input.
 *
 * @note Due the current incompatibility of Input type 'Date' (cross-browsing), Date Input must be of Text type.
 * @todo Add rule isSet in order to disable possibility to contain an empty string
 * Date: 26/12/13 00:30
 */

namespace engine\drivers\Inputs;

use engine\drivers\Exceptions\RuleException;
use engine\drivers\Input;

/**
 * Class Date
 * @package engine\drivers\Inputs
 */
class Date extends Input
{

    /**
     * Default error messages.
     */
    const MSG_INVALID_DATE = "Parameter '%s' in field '%s' is not a valid date.";
    const MSG_MIN_DATE_EXCEEDED = "Date '%s' in field '%s' is sooner the minimum '%s'.";
    const MSG_MAX_DATE_EXCEEDED = "Date '%s' in field '%s' is later the maximum '%s'.";

    /**
     * Date Input constructor.
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        // Setting field name
        $this->_fieldName = $fieldName;

        // Initializing valid rules for checkbox inputs
        $this->_validRules = array(
            'minDate',
            'maxDate'
        );

        // Verifying that input fulfills the most basic conditions this kind of input requires.
        try {
            $this->setDate();
        } catch (RuleException $rEx) {
            $this->setError($rEx);
        }
    }

    /**
     * This function checks and sets the Date input.
     * An empty date is considered valid.
     *
     * @throws RuleException
     * @todo Notice that, at this point, this function validates dates in Spanish format - dd/mm/yyyy i.e - 21/03/2014.
     */
    private function setDate()
    {
        // In case field is empty a RuleException is sent.
        if (!isset($_POST[$this->getFieldName()])) {
            $this->setValue('');
            throw new RuleException($this, 'set', '', sprintf(self::MSG_EMPTY_INPUT, $this->getFieldName()));
        }

        // Validating the Date as date.
        $this->setValue($_POST[$this->getFieldName()]);

        // If value is an empty string, it does not validate it.
        if ('' === $this->getValue()) {
            return;
        }
        $this->validateStringAsDate($this->getValue());
    }

    private function validateStringAsDate($string)
    {
        $amount = substr_count($string, '/');
        if (2 != $amount) {
            throw new RuleException($this, 'set', $string, sprintf(self::MSG_INVALID_DATE, $string, $this->getFieldName()));
        }

        list($day, $month, $year) = explode('/', $string);
        if (!checkdate($month, $day, $year)) {
            throw new RuleException($this, 'set', $string, sprintf(self::MSG_INVALID_DATE, $string, $this->getFieldName()));
        }
    }

    /**
     * Minimum date.
     * @param string $minDate Minimum date allowed in 'dd/mm/yyyy' format.
     * @throws RuleException triggered if Input date is sooner than allowed.
     */
    protected function minDate($minDate)
    {
        $inputDate = \DateTime::createFromFormat('d/m/Y', $this->getValue());
        $dateTimeMinDate = \DateTime::createFromFormat('d/m/Y', $minDate);

        if ($inputDate < $dateTimeMinDate) {
            throw new RuleException ($this, 'minDate', $this->getValue(), sprintf(self::MSG_MIN_DATE_EXCEEDED, $this->getValue(), $this->getFieldName(), $minDate));
        }
    }

    /**
     * Maximum date.
     * @param string $maxDate Maximum date allowed in 'dd/mm/yyyy' format.
     * @throws RuleException triggered if Input date is later than allowed.
     */
    protected function maxDate($maxDate)
    {
        $inputDate = \DateTime::createFromFormat('d/m/Y', $this->getValue());
        $dateTimeMaxDate = \DateTime::createFromFormat('d/m/Y', $maxDate);

        if ($inputDate > $dateTimeMaxDate) {
            throw new RuleException ($this, 'maxDate', $this->getValue(), sprintf(self::MSG_MAX_DATE_EXCEEDED, $this->getValue(), $this->getFieldName(), $maxDate));
        }
    }
}