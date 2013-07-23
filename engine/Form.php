<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * This class manages the collection of Post data, allowing validations.
 * Date: 16/06/13 21:45
 *
 * @todo Add exception trigger in the Fetch if data requested was not set.
 * @todo Create a special Exception type that allows logic to filter the exceptions triggered by the Form.
 * @todo Form work with both Get and Post options.
 * @todo Validations have to allow Strict Mode as third optional parameter.
 * @todo If Strict Mode is not enabled, Form should parse parameters type to the expected ones. Example: Form requires the item 'id' which is an Int, with Strict Mode disabled. When fetching it, the Form retrieves it as Int, even if it was retrieved as String.
 * @todo Validation type 'password' required. This validation would be different than the others when errors being found; password must never be displayed to the user.
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
     * @param string $field The HTML field name of the post.
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
     * @param string $field Name of the parameter required
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
     * @param string $type Validation type of the object to be validated.
     * @param array $rules List of rules that the object must accomplished to be validated.
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
     * @param string $key Parameter that failed
     * @param string $explanation Why parameter did not pass validation.
     * @todo The errors should contain a code to the validation that failed. The explanation should be an option to the object that receives the error to display it or not, but the object should have access to the information regarding what validation failed.
     */
    private function addError($key, $explanation)
    {
        $this->_errors[$key] = $explanation;
    }

    /**
     * Returns errors array.
     * @return array Errors array.
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}