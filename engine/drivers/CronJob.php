<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * @date 27/03/14 19:46
 */

namespace engine\drivers;


use ActiveRecord\DateTime;
use application\models\CronJob as CronJobModel;

class CronJob
{
    protected static $_STATE_IDLE = 'idle';
    protected static $_STATE_RUNNING = 'running';
    protected static $_STATE_STOPPED = 'stopped';

    /**
     * Here is stored the model.
     * @var CronJobModel $cronJobModel
     */
    protected $cronJobModel;

    public function __construct(CronJobModel $cronJobModel)
    {
        $this->cronJobModel = $cronJobModel;
    }

    /**
     * Returns whether is run time or not.
     * A CronJob will answer that is run time when:
     * 1) It has never been run before.
     * 2) The time lapsed since last run time is equal or higher than time set in frequency.
     * @return bool
     */
    public function isRunTime()
    {
        // No run time for already running cron jobs
        if ($this->cronJobModel->state == self::$_STATE_RUNNING) {
            return false;
        }

        // Run time for never executed cron jobs.
        if (is_null($this->cronJobModel->last_run)) {
            return true;
        }

        // Calculating time lapsed since last time.
        $lapsedTime = time() - $this->cronJobModel->last_run->getTimestamp();

        // Returning if seconds passed since last run are equal or higher than frequency (in seconds) required for this cron job.
        return ($lapsedTime >= $this->cronJobModel->frequency);
    }

    /**
     * This cron job logic is to be implemented in the extending class.
     * This parent class only sets the cron job model as running.
     */
    public function run()
    {
        $this->cronJobModel->state = self::$_STATE_RUNNING;
        $this->cronJobModel->save();
    }

    /**
     * Sets the cron job as idle.
     * Updates the last time this cron job run to now.
     */
    protected function stopRun()
    {
        $this->cronJobModel->state = self::$_STATE_IDLE;
        $this->cronJobModel->last_run = new \DateTime();
        ;
        $this->cronJobModel->save();
    }
}