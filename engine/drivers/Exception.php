<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Generic Hecnel Exception. Extends the Exception class of PHP Core.
 * @ate: 12/07/13 17:30
 */

namespace engine\drivers;

/**
 * Class Exception
 * @package engine\drivers
 */
class Exception extends \Exception
{
    /**
     * Fatal Exception. Related to Exception that are not suppose to happen, ever. Probably related to Application or System bugs or architectural flaws.
     */
    const FATAL_EXCEPTION = 0;

    /**
     * General Exception. Related to Exceptions that might happen along the Application stopping its regular execution.
     */
    const GENERAL_EXCEPTION = 1;

    /**
     * Danger Exception. Related to Exceptions that does not necessarily stop the Application regular execution but that might lead to potentially serious issues.
     */
    const DANGER_EXCEPTION = 2;

    /**
     * Warning Exception. Related to Exceptions that does not lead to a stop in the regular execution. Their purpose depends on the design of the Application, as in some cases
     * the design might prefer to stop the execution to present this kind of warnings, while in other cases it might prefer to continue the execution.
     */
    const WARNING_EXCEPTION = 3;

    /**
     * Alert Exception. Related to Exceptions that are not an Application issue. Their purpose is informative, similar to Warning Exceptions; the main difference is that an Alert
     * is never generated by a logical issue, while Warnings might.
     */
    const ALERT_EXCEPTION = 4;

    /**
     * Debug Exceptions. Artificially generated Exceptions for debugging purposes. Never to be shown in Production, although it might be stored for posterior analysis purposes.
     */
    const DEBUG_EXCEPTION = 5;

    /**
     * Exception type of this Exception.
     * @var int $exceptionType
     */
    protected $exceptionType = 0;

    public function __construct($message = "", $exceptionType = self::GENERAL_EXCEPTION, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setExceptionType($exceptionType);
    }

    /**
     * Sets the Error Level of this Exception.
     * @param int $exceptionType
     */
    public function setExceptionType($exceptionType)
    {
        $this->exceptionType = (int)$exceptionType;
    }

    /**
     * Gets the Exception Type of this Exception.
     * @return int
     */
    public function getExceptionType()
    {
        return (int)$this->exceptionType;
    }

    /**
     * Returns the relative file path.
     * @return string $relativeFilePath
     */
    public function getRelativeFile()
    {
        return substr($this->file, strlen(_SYSTEM_ROOT_PATH));
    }

    /**
     * Returns the custom backtrace of the Exception.
     * Each backtrace record is composed by the following parameters:
     *      file - The relative file path
     *      line - Line in which the exception was created in given file.
     *      function - Name of the function that generated the exception.
     *      class - Name of the class that contains the previous function.
     * @return array $customBacktrace
     */
    public function getCustomTrace()
    {
        $traces = $this->getTrace();
        $customTraces = array();

        foreach ($traces as $i => $trace) {
            if (isset($trace['file'])) {
                $customTraces[$i]['file'] = substr($trace['file'], strlen(_SYSTEM_ROOT_PATH));
            }

            if (isset($trace['line'])) {
                $customTraces[$i]['line'] = $trace['line'];
            }

            if (isset($trace['function'])) {
                $customTraces[$i]['function'] = $trace['function'];
            }

            if (isset($trace['class'])) {
                $customTraces[$i]['class'] = $trace['class'];
            }
        }

        return $customTraces;
    }
}