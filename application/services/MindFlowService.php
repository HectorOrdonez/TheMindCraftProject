<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Service that manages the MindFlow page's logic.
 * Date: 14/01/14 18:30
 */

namespace application\services;

use application\engine\Service;
use application\models\Idea;

use application\models\Mission;
use application\models\Routine;
use engine\drivers\Exception;

class MindFlowService extends Service
{
    const TYPE_MISSION = 'mission';
    const TYPE_ROUTINE = 'routine';
    const SELECTED_TRUE = 1;
    const SELECTED_FALSE = 0;
    const IMPORTANT_TRUE = 1;
    const IMPORTANT_FALSE = 0;
    const URGENT_TRUE = 1;
    const URGENT_FALSE = 0;

    /**
     * Service constructor of MindFlow logic.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ideas request. Returns a list of ideas with different conditionals and different output fields, depending on the
     * purpose of the request (step).
     *
     * @param int $userId User Id requesting ideas.
     * @param string $step Stage for which the list is for.
     * @return array
     * @throws Exception
     */
    public function getIdeas($userId, $step)
    {
        // Initializing parameters
        $response = array(); // Array that will be returned.

        switch ($step) {
            case 'brainStorm':
                /**
                 * @var Idea[] $rawIdeasArray
                 */
                $rawIdeasArray = Idea::find('all', array('user_id' => $userId));

                foreach ($rawIdeasArray as $idea) {
                    $response[] = $idea->toArray(array('id', 'title', 'date_creation'));
                }
                break;
            case 'select':
                /**
                 * @var Idea[] $rawIdeasArray
                 */
                $rawIdeasArray = Idea::find('all', array('user_id' => $userId));

                foreach ($rawIdeasArray as $idea) {
                    $response[] = $idea->toArray(array('id', 'title', 'date_creation', 'selected'));
                }
                break;
            case 'prioritize':
                /**
                 * @var Idea[] $rawIdeasArray
                 */
                $rawIdeasArray = Idea::find('all', array(
                        'user_id' => $userId,
                        'selected' => self::SELECTED_TRUE)
                );

                foreach ($rawIdeasArray as $idea) {
                    $response[] = $idea->toArray(array('id', 'title', 'date_creation', 'important', 'urgent'));
                }
                break;
            case 'applyTime':
                // Getting missions
                $response['missions'] = array();
                /**
                 * @var Mission[] $rawMissionsArray
                 */
                $rawMissionsArray = Mission::all(array(
                    'joins' => array('idea'),
                    'conditions' => array(
                        'selected = ? AND user_id = ?', self::SELECTED_TRUE, $userId
                    )));

                foreach ($rawMissionsArray as $mission) {
                    $response['missions'][] = $mission->toArray(array('id', 'title', 'date_todo', 'time_from', 'time_till'));
                }

                // Getting routines
                $response['routines'] = array();
                /**
                 * @var Routine[] $rawRoutinesArray
                 */
                $rawRoutinesArray = Routine::all(array(
                    'joins' => array('idea'),
                    'conditions' => array(
                        'selected = ? AND user_id = ?', self::SELECTED_TRUE, $userId
                    )));

                foreach ($rawRoutinesArray as $routine) {
                    $response['routines'][] = $routine->toArray(array('id', 'title', 'frequency_days', 'frequency_weeks', 'date_start', 'date_finish', 'time_from', 'time_till'));
                }

                break;
            default:
                throw new Exception('Unexpected step ' . $step . ' ');
        }

        return $response;
    }


//        
//        
//        
///**
//        $ideas = $this->getIdeas($userId, $step);
//        $routines = $this->getRoutines($userId, $step);
//
//        if ($step == 'applyTime') {
//            foreach ($ideas as $idea) {
//                $ideaKey = 'idea_' . $idea['id'];
//                $response[$ideaKey] = $idea;
//                $response[$ideaKey]['type'] = 'idea';
//                $response[$ideaKey]['frequency_days'] = '';
//                $response[$ideaKey]['frequency_weeks'] = '';
//                $response[$ideaKey]['date_start'] = '';
//                $response[$ideaKey]['date_finish'] = '';
//            }
//            foreach ($routines as $routine) {
//                $routineKey = 'routine_' . $routine['id'];
//                $response[$routineKey] = $routine;
//                $response[$routineKey]['type'] = 'routine';
//                $response[$routineKey]['date_todo'] = '';
//            }
//        } else {
//            foreach ($ideas as $idea) {
//                $response[$idea['id']] = $idea;
//            }
//            foreach ($routines as $routine) {
//                $response[$routine['id']] = $routine;
//            }
//        }
//        ksort($response);
//        return $response;
//    }
//
//    function getIdeas($userId, $step)
//    {
//        $ideasArray = array();
//
//        list($requiredFields, $requiredConditions) = $this->extractRequirements($step, 'idea', $userId);
//
//        /**
//         * @var Idea[] $rawIdeas
//         */
//        $rawIdeas = Idea::find('all', array('conditions' => $requiredConditions));
//
//        foreach ($rawIdeas as $rawIdea) {
//            $ideasArray[] = $rawIdea->toArray($requiredFields);
//        }
//        return $ideasArray;
//    }
//
//    function getRoutines($userId, $step)
//    {
//        $routinesArray = array();
//
//        list($requiredFields, $requiredConditions) = $this->extractRequirements($step, 'routine', $userId);
//
//        /**
//         * @var Routine[] $rawRoutines
//         */
//        $rawRoutines = Routine::find('all', array('conditions' => $requiredConditions));
//
//        foreach ($rawRoutines as $rawRoutine) {
//            $routinesArray[] = $rawRoutine->toArray($requiredFields);
//        }
//        return $routinesArray;
//    }
//
//    /**
//     * This method is a helper for the getIdeas request.
//     * Based on the request step, the required fields and conditions differ, hence this method.
//     *
//     * @param string $step The requested step.
//     * @param int $userId The requesting user id.
//     * @param string $ideaType Either onetime or routine
//     * @return array The fields and conditions.
//     */
//    private function extractRequirements($step, $ideaType, $userId)
//    {
//        // Default fields and conditions, common in all steps
//        $requiredFields = array('id', 'title'); // Fields to output, depending on step.
//        $requiredConditions = array('user_id' => $userId); // Conditions that ideas must accomplished to be added to the response.
//
//        // Setting the required fields and conditions depending on step.
//        switch ($step) {
//            case 'brainStorm':
//                $requiredFields[] = 'date_creation';
//                break;
//            case 'select':
//                $requiredFields[] = 'date_creation';
//                $requiredFields[] = 'selected';
//                break;
//            case 'prioritize':
//                $requiredFields[] = 'date_creation';
//                $requiredFields[] = 'important';
//                $requiredFields[] = 'urgent';
//                $requiredConditions['selected'] = self::SELECTED_TRUE; // Conditions that ideas must accomplished to be added to the response.
//                break;
//            case 'applyTime':
//                if ($ideaType == 'idea') {
//                    $requiredFields[] = 'date_todo';
//                    $requiredFields[] = 'time_from';
//                    $requiredFields[] = 'time_till';
//                }
//
//                if ($ideaType == 'routine') {
//                    $requiredFields[] = 'date_start';
//                    $requiredFields[] = 'date_finish';
//                    $requiredFields[] = 'time_from';
//                    $requiredFields[] = 'time_till';
//                    $requiredFields[] = 'frequency_days';
//                    $requiredFields[] = 'frequency_weeks';
//                }
//                $requiredConditions['selected'] = self::SELECTED_TRUE; // Conditions that ideas must accomplished to be added to the response.
//                break;
//        }
//
//        return array($requiredFields, $requiredConditions);
//    }

    /**
     * Creates an idea related to given user.
     *
     * @param string $userId
     * @param string $title
     * @return array
     */
    public function newIdea($userId, $title)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::create(array(
            'user_id' => $userId,
            'title' => $title,
            'date_creation' => date('Y-m-d')
        ));
        Mission::create(array('idea_id' => $idea->id));

        return $idea->toArray(array('id', 'title', 'date_creation', 'selected'));
    }

    /**
     * Edit Idea
     *
     * @param int $userId
     * @param int $ideaId
     * @param string $newTitle
     * @throws Exception
     */
    public function editIdea($userId, $ideaId, $newTitle)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (null === $idea OR $userId != $idea->user_id) {
            throw new Exception('The idea you are trying to modify does not exist or it is not yours.');
        }

        $idea->title = $newTitle;
        $idea->save();
    }

    /**
     * Delete idea
     *
     * @param int $userId
     * @param int $ideaId
     * @throws Exception
     */
    public function deleteIdea($userId, $ideaId)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (null === $idea OR $userId != $idea->user_id) {
            throw new Exception('The idea you are trying to delete does not exist or it is not yours.');
        }

        $idea->delete();
    }

    /**
     * Sets the date, time and frequency of an idea.
     * The parameters received can be partially empty, as an idea might have frequency defined but no date or time, and viceversa.
     * @param int $userId Current User
     * @param int $ideaId Idea which t ime is being applied.
     * @param string $date Date in which this idea needs to be done. Empty string if not required.
     * @param string $time Time in which this idea needs to be done. Empty string if not required.
     * @param array $frequency Array of days that User wants to do this idea. Empty array if not required.
     * @throws Exception
     */
    public function applyTimeToIdea($userId, $ideaId, $date, $time, $frequency)
    {
        if (0 == strlen($date) and
            0 == strlen($time) and
                0 == count($frequency)
        ) {
            throw new Exception ('This request is not changing anything.');
        } else {
            if (0 == strlen($date)) {
                $date = NULL;
            }
            if (strlen($time) == 0) {
                $time = NULL;
            }
        }

        // Turn Frequency array into frequency string
        // Ex: 1000000 is every monday. 
        // Ex: 0100000 is every tuesday. 
        // Ex: 0001001 is every thursday and sunday. 
        $parsedFrequency = array(0, 0, 0, 0, 0, 0, 0);
        if (count($frequency) > 0) {
            foreach ($frequency as $day) {
                switch ($day) {
                    case 'monday':
                        $parsedFrequency[0] = 1;
                        break;
                    case 'tuesday':
                        $parsedFrequency[1] = 1;
                        break;
                    case 'wednesday':
                        $parsedFrequency[2] = 1;
                        break;
                    case 'thursday':
                        $parsedFrequency[3] = 1;
                        break;
                    case 'friday':
                        $parsedFrequency[4] = 1;
                        break;
                    case 'saturday':
                        $parsedFrequency[5] = 1;
                        break;
                    case 'sunday':
                        $parsedFrequency[6] = 1;
                        break;
                }
            }
        }
        $parsedFrequency = implode('', $parsedFrequency);

        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea to which you are trying to apply time does not exist or it is not yours.');
        }

        $idea->date_todo = $date;
        $idea->time_todo = $time;
        $idea->frequency = $parsedFrequency;
        $idea->save();
    }

    /**
     * Set idea selection state
     * @param $userId
     * @param $ideaId
     * @param string $selectionState True or false.
     * @throws \engine\drivers\Exception
     */
    public function setIdeaSelectionState($userId, $ideaId, $selectionState)
    {
        // Parsing boolean to num
        $selectedValue = ('true' === $selectionState) ? self::SELECTED_TRUE : self::SELECTED_FALSE;

        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea you are trying to select or unselect does not exist or it is not yours.');
        }

        $idea->selected = $selectedValue;
        $idea->save();
    }

    /**
     * Set idea important state
     * @param $userId
     * @param $ideaId
     * @param string $importantState True or false.
     * @throws \engine\drivers\Exception
     */
    public function setIdeaImportantState($userId, $ideaId, $importantState)
    {
        // Parsing boolean to num
        $importantValue = ('true' === $importantState) ? self::IMPORTANT_TRUE : self::IMPORTANT_FALSE;

        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea you are trying to set to important or not important does not exist or it is not yours.');
        }

        $idea->important = $importantValue;
        $idea->save();
    }

    /**
     * Set idea urgent state
     * @param $userId
     * @param $ideaId
     * @param string $urgentState True or false.
     * @throws \engine\drivers\Exception
     */
    public function setIdeaUrgentState($userId, $ideaId, $urgentState)
    {
        // Parsing boolean to num
        $urgentValue = ('true' === $urgentState) ? self::URGENT_TRUE : self::URGENT_FALSE;

        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea you are trying to set to urgent or not urgent does not exist or it is not yours.');
        }

        $idea->urgent = $urgentValue;
        $idea->save();
    }

    /**
     *
     */
    public function setMissionDateTime($userId, $ideaId, $dateTodo, $timeFrom, $timeTill)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea which Date and/or Time you are trying to set does not exist or it is not yours.');
        }

        $mission = $this->convertIdeaToMission($idea);
        $mission->date_todo = $dateTodo;
        $mission->time_from = $timeFrom;
        $mission->time_till = $timeTill;
        $mission->save();
    }

    public function setRoutineDateTime($userId, $ideaId, $dateStart, $dateFinish, $timeFrom, $timeTill)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea which Date and/or Time you are trying to set does not exist or it is not yours.');
        }

        $routine = $this->convertIdeaToRoutine($idea);
        $routine->date_start = $dateStart;
        $routine->date_finish = $dateFinish;
        $routine->time_from = $timeFrom;
        $routine->time_till = $timeTill;
        $routine->save();
    }

    private function convertIdeaToRoutine(Idea $idea)
    {
        // Get Idea type 
        $ideaType = $idea->type;

        switch ($ideaType) {
            case self::TYPE_MISSION:
                $newRoutine = Routine::create(array('idea_id' => $idea->id));
                $missionToDelete = Mission::find_by_pk($idea->id);
                $missionToDelete->delete();
                
                $idea->type = self::TYPE_ROUTINE;
                $idea->save();
                return $newRoutine;
                break;
            case self::TYPE_ROUTINE:
                return Routine::find_by_pk($idea->id);
                break;
            default:
                throw new Exception('Unknown Idea type.');
        }
    }
    
    private function convertIdeaToMission(Idea $idea)
    {
        // Get Idea type 
        $ideaType = $idea->type;

        switch ($ideaType) {
            case self::TYPE_MISSION:
                return Mission::find_by_pk($idea->id);
                break;
            case self::TYPE_ROUTINE:
                $newMission = Mission::create(array('idea_id' => $idea->id));
                $routineToDelete = Routine::find_by_pk($idea->id);
                $routineToDelete->delete();

                $idea->type = self::TYPE_MISSION;
                $idea->save();
                return $newMission;
                break;
            default:
                throw new Exception('Unknown Idea type.');
        }
    }
}