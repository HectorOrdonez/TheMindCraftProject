<?php
/**
 * General TODOS in the The Mindcraft Project Application
 *
 * @todo Redirection page. Build a page that is load when User tries to go somewhere it can not go, that redirects him or her to its place (index or main).
 * @todo Remove shaking effect when hovering effects in the images of the Main menu and the MindFlow top menu.
 */

// First thing ever, session_start.
session_start();

/**
 * Loading System Configs
 */
require_once 'application/config/system.php';
require_once 'application/config/database.php';
require_once 'engine/php-activerecord/ActiveRecord.php';

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

/**
 * Initializing ActiveRecord
 */
ActiveRecord\Config::initialize(function ($cfg) {
    $cfg->set_model_directory('application/models');
    $cfg->set_connections(array('development' => DB_TYPE . '://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME));
});

use engine\Bootstrap;

// Run app
$application = new Bootstrap();
$application->set_DEFAULT_CONTROLLER(_DEFAULT_CONTROLLER);
$application->set_DEFAULT_METHOD(_DEFAULT_METHOD);
$application->set_ERROR_CONTROLLER(_ERROR_CONTROLLER);
$application->begin();