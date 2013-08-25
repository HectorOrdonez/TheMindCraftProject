<?php
/**
 * General TODOS in the The Mindcraft Project Application
 */

use engine\Bootstrap;

/**
 * Loading System Configs
 */
require 'config/system.php';
require 'config/database.php';

/**
 * Defining Autoload function
 */
function __autoload($class)
{
    // The required class uses \ as directory separator (because of the namespace usage. This needs to be replaced with the real directory separator.
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Constructing the file path
    $file = _SYSTEM_ROOT_PATH . $class . '.php';

    if (is_readable($file)) {
        require_once $file;

    } else {
        $msg = 'Critical failure trying to Autoload the Class [' . $class . ']. The expected location is [' . $file . ' ] but was not found.';
        header("HTTP/1.1 500 " . $msg);
        exit($msg);
    }

}
// Run app
$application = new Bootstrap();
$application->set_DEFAULT_CONTROLLER(_DEFAULT_CONTROLLER);
$application->set_DEFAULT_METHOD(_DEFAULT_METHOD);
$application->set_ERROR_CONTROLLER(_ERROR_CONTROLLER);
$application->begin();