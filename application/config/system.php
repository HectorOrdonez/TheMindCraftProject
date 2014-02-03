<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Date: 23/07/13 14:00
 */

// Sets the language and the number format of the application.
setlocale(LC_NUMERIC, 'es_ES');

// Informs the System about which setup use
define ('_PRODUCTION', FALSE);

// Base path of the website.
if (_PRODUCTION === TRUE) {
    define ('_SYSTEM_BASE_URL', 'http://www.themindcraftproject.org/');
} else {
    //define ('_SYSTEM_BASE_URL', 'http://192.168.1.55/projects/themindcraftproject/');
    define ('_SYSTEM_BASE_URL', 'http://localhost/projects/themindcraftproject/');
}

// Root path of the project in the server.
define ('_SYSTEM_ROOT_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);

/**********************************/
/** CONFIG SETTINGS FOR HECNEL **/
/**********************************/
define ('_DEFAULT_CONTROLLER', 'index');
define ('_DEFAULT_METHOD', 'index');
define ('_ERROR_CONTROLLER', 'error');
define ('_EXCEPTION_METHOD', 'exception');

/**
 * Config required for ActiveRecord. It disables its autoloading function.
 */
define ('PHP_ACTIVERECORD_AUTOLOAD_DISABLE', true);

define ('EXCEPTION_SIGNUP_USERNAME_IN_USE', 1001);
define ('EXCEPTION_SIGNUP_MAIL_IN_USE', 1002);
define ('EXCEPTION_LOGIN_FAILED', 1003);
define ('EXCEPTION_LOGIN_USER_NOT_ACTIVE', 1004);