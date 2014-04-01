<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * @date: 13/12/13 15:30
 */

namespace engine;

use application\models\CronJob as CronJobModel;
use engine\drivers\Exception;

/**
 * Class CronJob
 * @package engine
 */
class CronJob
{
    private static $namespaceRoute = 'engine\drivers\CronJobs\\';
    
    // This is not an instantiable class.
    private function __construct()
    {
    }

    /**
     * Calls to Input are in the format: Input::$type($fieldName);
     *
     * E.g Input::text('username') will return an object Text, which extends the class Input, with 'username' as parameter fieldName.
     * @param CronJobModel $cronJobModel
     * @return \engine\drivers\CronJob
     * @throws drivers\Exception
     */
    public static function build(CronJobModel $cronJobModel)
    {
        $class = self::$namespaceRoute . $cronJobModel->driver;
        
        if (!class_exists($class, true))
        {
            throw new Exception('The CronJob ' . $cronJobModel->name . ' does not exist', Exception::DANGER_EXCEPTION);
        }
        
        return new $class($cronJobModel);
    }
}