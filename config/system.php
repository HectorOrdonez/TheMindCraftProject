<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Date: 23/07/13 14:00
 */
// Informs the System about which setup use
define ('_PRODUCTION', FALSE);

// Base path of the website.
if (_PRODUCTION === TRUE) {
    define ('_SYSTEM_BASE_URL', 'http://themindcraftproject.org/');
} else {
    define ('_SYSTEM_BASE_URL', 'http://192.168.192.13/projects/themindcraftproject/');
}

// Root path of the project in the server.
define ('_SYSTEM_ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**********************************/
/** CONFIG SETTINGS FOR HECNEL **/
/**********************************/
define ('_DEFAULT_CONTROLLER', 'index');
define ('_DEFAULT_METHOD', 'index');
define ('_ERROR_CONTROLLER', 'error');
define ('_EXCEPTION_METHOD', 'exception');