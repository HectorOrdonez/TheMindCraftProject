<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Mail Input.
 * @date: 23/12/13 16:30
 */

namespace engine\drivers\Inputs;

use engine\drivers\Exceptions\InputException;
use engine\drivers\Exceptions\RuleException;
use engine\drivers\Input;

/**
 * Class Mail
 * @package engine\drivers\Inputs
 */
class Mail extends Input
{
    /**
     * Constant that defines the minimum string length to display when Input value exceeds maxLength rule.
     */
    const MIN_DISPLAYABLE_LEN = 10;
    /**
     * Default error messages.
     */
    const MSG_MAX_LENGTH_EXCEEDED = "Parameter '%s' length in field '%s' exceeds the maximum '%s'.";
    const MSG_MIN_LENGTH_NOT_REACHED = "Parameter '%s' length in field '%s' does not reach the minimum '%s'.";
    const MSG_INVALID_MAIL = "Parameter '%s' in field '%s' is not a valid mail.";

    /**
     * Mail Input constructor.
     * @param $fieldName
     * @throws RuleException
     */
    public function __construct($fieldName)
    {
        // Setting field name
        $this->_fieldName = $fieldName;

        // Initializing valid rules for mail inputs
        $this->_validRules = array(
            'minLength',
            'maxLength'
        );

        // Verifying that input fulfills the most basic conditions this kind of input requires.
        try {
            if (!isset($_POST[$fieldName]) or '' == $_POST[$fieldName]) {
                $this->setValue('');
                throw new RuleException($this, 'set', '', sprintf(self::MSG_EMPTY_INPUT, $fieldName));
            } else {
                $this->setValue($_POST[$fieldName]);
                $this->isMail();
            }
        } catch (RuleException $rEx) {
            $this->setError($rEx);
        }
    }

    /**
     * Verifies on Input construction that value is a valid mail address.
     * @throws RuleException triggered if filter_var does not validate the input parameter as mail.
     */
    private function isMail()
    {
        if (false === filter_var($this->getValue(), FILTER_VALIDATE_EMAIL)) {
            throw new RuleException($this, 'set', $this->getValue(), sprintf(self::MSG_INVALID_MAIL, $this->getValue(), $this->getFieldName()));
        }
    }

    /**
     * Minimum length of the mail.
     * @param int $minLen Minimum length of the string
     * @throws RuleException triggered if string length is lower than expected.
     */
    protected function minLength($minLen)
    {
        if (strlen($this->getValue()) < $minLen) {
            throw new RuleException ($this, 'minLength', $this->getValue(), sprintf(self::MSG_MIN_LENGTH_NOT_REACHED, $this->getValue(), $this->getFieldName(), $minLen));
        }
    }

    /**
     * Maximum length of the mail.
     * @param int $maxLen Maximum length of the string
     * @throws RuleException triggered if string length is greater than expected.
     */
    protected function maxLength($maxLen)
    {
        if (strlen($this->getValue()) > $maxLen) {
            $value = ($maxLen < self::MIN_DISPLAYABLE_LEN) ? $this->getValue() : substr($this->getValue(), 0, $maxLen) . '[...]';
            $this->setValue($value);
            throw new RuleException ($this, 'maxLength', $this->getValue(), sprintf(self::MSG_MAX_LENGTH_EXCEEDED, $this->getValue(), $this->getFieldName(), $maxLen));
        }
    }
}