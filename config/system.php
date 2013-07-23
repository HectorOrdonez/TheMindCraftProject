<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Date: 11/06/13 13:47
 */

// Informs the System about which setup use
define ('_PRODUCTION', FALSE);

// Base path of the website.
if (_PRODUCTION === TRUE) {
    define ('_SYSTEM_BASE_URL', 'unknown');
} else {
    define ('_SYSTEM_BASE_URL', 'http://localhost/projects/selfology/');
}

// Root path of the project in the server.
define ('_SYSTEM_ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**********************************/
/** CONFIG SETTINGS FOR HECNEL **/
/**********************************/
define ('_DEFAULT_CONTROLLER', 'index');
define ('_DEFAULT_METHOD', 'index');
define ('_ERROR_CONTROLLER', 'Error');
define ('_EXCEPTION_METHOD', 'exception');