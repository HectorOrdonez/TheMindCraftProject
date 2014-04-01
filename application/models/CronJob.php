<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * This model is required for Hecnel Framework CronJob system. It is used to get the required cron jobs from the DB.
 *  
 * @date 27/03/14 20:38
 */

namespace application\models;

use ActiveRecord\Model;

/**
 * Class CronJob/**
 * @package application\models
 *
 * Magic methods ...
 *
 * Magically accessed attributes ...
 * @property int $id
 * @property string $name
 * @property string $driver
 * @property string $state ['idle', 'running', 'stopped']
 * @property \DateTime $last_run
 * @property int $frequency
 */
class CronJob extends Model
{
    public static $table_name = 'cronjob'; // Table name
}