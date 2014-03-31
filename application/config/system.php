<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Date: 23/07/13 14:00
 */

/** Sets the language and the number format of the application. **/
setlocale(LC_NUMERIC, 'es_ES');

/** Informs the System about which setup use **/
define ('_PRODUCTION', FALSE);

/** Base path of the website. **/
if (_PRODUCTION === TRUE) {
    define ('_SYSTEM_BASE_URL', 'http://themindcraftproject.org/');
} else {
    //define ('_SYSTEM_BASE_URL', 'http://192.168.1.55/projects/themindcraftproject/');
    define ('_SYSTEM_BASE_URL', 'http://localhost/projects/themindcraftproject/');
}

/**
 * Default controllers and methods for index and error page.
 */
define ('_DEFAULT_CONTROLLER', 'index');
define ('_DEFAULT_METHOD', 'index');
define ('_ERROR_CONTROLLER', 'Error');
define ('_EXCEPTION_METHOD', 'exception');

/**
 * Exception codes
 */
define ('EXCEPTION_SIGNUP_USERNAME_IN_USE', 1001);
define ('EXCEPTION_SIGNUP_MAIL_IN_USE', 1002);
define ('EXCEPTION_LOGIN_FAILED', 1003);
define ('EXCEPTION_LOGIN_USER_NOT_ACTIVE', 1004);

/***************************************************************/
/** CONFIG SETTINGS FOR HECNEL                                **/
/** CHANGE OF THIS SETTING MIGHT LEAD TO UNEXPECTED BEHAVIOR. **/
/***************************************************************/

/**
 * Root path of the project in the server.
 */
define ('_SYSTEM_ROOT_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);

/**
 * Paths to Hecnel folders.
 */
define ('_FOLDER_ENGINE', _SYSTEM_ROOT_PATH . 'engine' . DIRECTORY_SEPARATOR);
define ('_FOLDER_LOG', _SYSTEM_ROOT_PATH . join(DIRECTORY_SEPARATOR, array('engine', 'log')) . DIRECTORY_SEPARATOR);
define ('_FOLDER_LOG_DEBUG', _SYSTEM_ROOT_PATH . join(DIRECTORY_SEPARATOR, array('engine', 'log','debug')) . DIRECTORY_SEPARATOR);
define ('_FOLDER_LOG_INFO', _SYSTEM_ROOT_PATH . join(DIRECTORY_SEPARATOR, array('engine', 'log','info')) . DIRECTORY_SEPARATOR);
define ('_FOLDER_LOG_ERROR', _SYSTEM_ROOT_PATH . join(DIRECTORY_SEPARATOR, array('engine', 'log','error')) . DIRECTORY_SEPARATOR);

/**
 * Config required for ActiveRecord. It disables its autoloading function.
 */
define ('PHP_ACTIVERECORD_AUTOLOAD_DISABLE', true);

/**************************************/
/** END CONFIG SETTINGS FOR HECNEL   **/
/**************************************/