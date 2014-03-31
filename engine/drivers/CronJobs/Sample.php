<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description: 
 * @date 27/03/14 20:35
 */

namespace engine\drivers\CronJobs;

use application\models\CronJob as CronJobModel;
use engine\drivers\CronJob;

class Sample extends CronJob
{
    public function __construct(CronJobModel $sampleModel)
    {
        parent::__construct($sampleModel);
    }
    
    
    public function run()
    {
        parent::run();

        // Sample logic.
        
        $this->stopRun();
    }

    /**
     * CronJobs stop method are optional.
     * To be used if this specific cron job stop method requires extra or different logic.
     * Otherwise, use default's one (parent method).
     */
    protected function stopRun()
    {
        parent::stopRun();
        
        // Sample stop logic.
    }
}