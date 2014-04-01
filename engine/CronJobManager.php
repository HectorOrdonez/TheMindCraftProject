<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * @date 26/03/14 13:58
 * @todo Check if cronjobs are stacked!
 */

/**
 * Loading System Configs
 */
require_once './../application/config/system.php';
require_once './../application/config/database.php';
require_once './../engine/php-activerecord/ActiveRecord.php';

/**
 * Defining Autoload function
 * @param string $class Name of the class to autoload. The string contains the namespace, which will be the folders, and the name of the file.
 */
function __autoload($class)
{
    // The required class uses \ as directory separator (because of the namespace usage. This needs to be replaced with the real directory separator.
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Constructing the file path
    $file = _SYSTEM_ROOT_PATH . $class . '.php';

    if (is_readable($file)) {
        require_once $file;
    }
}

/**
 * Initializing ActiveRecord
 */
ActiveRecord\Config::initialize(function ($cfg) {
    $cfg->set_model_directory(_SYSTEM_ROOT_PATH . join(DIRECTORY_SEPARATOR, array('application', 'models')));
    $cfg->set_connections(array('development' => DB_TYPE . '://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME));
});

/**
 * Initializing required classes
 */
use application\models\CronJob;
use engine\Log;

/**
 * Defining Cron Job log file name
 */
define ('CRONJOB_FILE', 'CronJob.txt');

/**
 * Cron Job Manager first opens and updates the Cron Job log.
 */
$logFile = fopen(_FOLDER_LOG_INFO . CRONJOB_FILE, 'a');
fwrite($logFile, date('m/d/Y G:i', time()) . "- Cronjob Manager executing. \r\n");

/**
 * Loading the idle Cron Jobs from DB.
 */
$cronJobs = CronJob::all(array(
        'conditions' => array('state = "idle"')
    )
);

/**
 * For each cron job:
 * 1) Tries to build the cron job driver. In case it does not exist an exception is stored in the error file.
 * 2) Checks if cron job needs to be run.
 * 3) In case cron job needs to be run a log is stored and the cron job is executed.
 */
foreach ($cronJobs as $cronJobRecord) {
    try {
        $cronJob = \engine\CronJob::build($cronJobRecord);
    } catch (Exception $e) {
        $errorFile = fopen(_FOLDER_LOG_ERROR . CRONJOB_FILE, 'a');
        $message = date('m/d/Y G:i', time()) . "- WARNING! Cronjob {$cronJobRecord->name} could not be loaded. Make sure its driver class is created in the CronJobs folder. \r\n";
        
        fwrite($logFile, $message);
        fwrite($errorFile, $message);
        fclose($errorFile);
        continue;
    }

    if ($cronJob->isRunTime()) {
        fwrite($logFile, date('m/d/Y G:i', time()) . '- Run Cronjob ' . $cronJobRecord->name . '.' . "\r\n");
        $cronJob->run();
    }
}

/**
 * End of Cron Job Manager: a log is stored and the file is closed.
 */
fwrite($logFile, date('m/d/Y G:i', time()) . "- Cronjob Manager finished. \r\n");
fclose($logFile);