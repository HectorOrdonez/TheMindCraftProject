<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * The Routine Executer.
 * @date 31/03/14 18:30
 */

namespace engine\drivers\CronJobs;

use application\models\CronJob as CronJobModel;
use application\models\Routine;
use engine\drivers\CronJob;

class RoutineExecutor extends CronJob
{
    public function __construct(CronJobModel $sampleModel)
    {
        parent::__construct($sampleModel);
    }
    
    
    public function run()
    {
        parent::run();

        // Getting Routines
        /**
         * @var Routine[] $routines
         */
        $routines = Routine::all(array(
                'joins' => array('idea'),
                'conditions' => array())
        );
        
        foreach($routines as $routine)
        {
            if ($routine->isGenerationNeeded())
            {
                $routine->generateActions();
            }
        }
        $this->stopRun();
    }
}