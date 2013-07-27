<?php
/**
 * General TODOS in the Selfology Application
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
    $file = _SYSTEM_ROOT_PATH . $class . '.php';
    if (is_readable($file))
    {
        require_once $file;
    }
    else
    {
        $msg = 'Fatal error on Autoload class : ' . $class . ' - not found.';

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