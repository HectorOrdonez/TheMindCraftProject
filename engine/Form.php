<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * This class manages the collection of Post data, allowing validations.
 * Date: 16/06/13 21:45
 */

namespace engine;

use engine\Validator;

class Form
{
    /**
     * Array of Posted parameters
     * @var array
     */
    private $_postData = array();

    /**
     * Current item. Allows adding Validation to the last item required.
     * @var string
     */
    private $_currentItem= NULL;

    /**
     * List of errors, if any.
     * @var array
     */
    private $_errors = array();

    /**
     * Form constructor.
     */
    public function __construct()
    {
    }

    /**
     * Sets the specified field as a required Post parameter.
     * Returns this form in order to allow concatenation.
     * @param $field string - The HTML field name of the post.
     * @return $this
     * @throws Exception If required item is not sent in the request.
     */
    public function requireItem($field)
    {
        if (!isset($_POST[$field]))
        {
            throw new Exception ('Required Item ' . $field . ' was not sent with the request.', Exception::GENERAL_EXCEPTION);
        }
        $this->_postData[$field] = $_POST[$field];
        $this->_currentItem = $field;
        return $this;
    }

    /**
     * Returns the specified parameter set in the Post.
     * @param $field string - Name of the parameter required
     * @throws Exception If field did not pass validation.
     */
    public function fetch($field)
    {
        if (isset($this->_errors[$field]))
        {
            throw new Exception ('Field ' . $field . ' did not pass Validation. Following error received: ' . $this->_errors[$field]);
        }
        return $this->_postData[$field];
    }

    /**
     * Sets validation control to the last parameter specified.
     * Returns this form in order to allow concatenation.
     * @param $type string - Validation type of the object to be validated.
     * @param $rules array - List of rules that the object must accomplished to be validated.
     * @return $this
     */
    public function validate($type, $rules=NULL)
    {
        try {
            Validator::$type($this->_postData[$this->_currentItem], $rules);
        }
        catch (Exception $e)
        {
            $this->addError($this->_currentItem, $e->getMessage());
        }

        return $this;
    }

    /**
     * Adds an error.
     * @param $key string - Failed parameter
     * @param $explanation string - for the parameter to fail the validation.
     */
    private function addError($key, $explanation)
    {
        $this->_errors[$key] = $explanation;
    }

    /**
     * Returns errors array.
     * @return array - List of errors.
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}