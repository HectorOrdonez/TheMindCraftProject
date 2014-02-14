<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Service that manages MindFlow logic:
 *  BrainStorm step, which allows new Ideas and their edition and deletion.
 *  Select step, as above plus selecting and un-selecting which ideas proceed with the flow.
 *  Prioritize step, which allows User to set Ideas importance and urgency.
 *  ApplyTime step, which enables Missions to have specified date and time frame and to convert them into Routines,
 *      with their related Date and Time frames, plus frequencies.
 *  PerForm step, to be implemented.
 *
 * Date: 14/01/14 18:30
 * @todo Implement PerForm page.
 */

namespace application\services;

use application\engine\Service;
use application\models\Action;
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
     * @param int $userId User id.
     * @param string $step Stage for which the list is for.
     * @return array List of ideas.
     * @throws Exception If requested step is unexpected.
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

    /**
     * Creates an idea related to given user.
     *
     * @param int $userId User id.
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
     * @param int $userId User id.
     * @param int $ideaId Idea id.
     * @param string $newTitle
     * @throws Exception If idea to be edited is not found or not owned by user.
     */
    public function editIdea($userId, $ideaId, $newTitle)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) OR $userId != $idea->user_id) {
            throw new Exception('The idea you are trying to modify does not exist or it is not yours.');
        }

        $idea->title = $newTitle;
        $idea->save();
    }

    /**
     * Delete idea
     *
     * @param int $userId User id.
     * @param int $ideaId Idea id.
     * @throws Exception If idea to be deleted is not found or not owned by user.
     */
    public function deleteIdea($userId, $ideaId)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) OR $userId != $idea->user_id) {
            throw new Exception('The idea you are trying to delete does not exist or it is not yours.');
        }

        $idea->delete();
    }

    /**
     * Set idea selection state
     *
     * @param int $userId User id.
     * @param int $ideaId Idea id.
     * @param string $selectionState True or false.
     * @throws Exception If idea being changed is not found or not owned by user.
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
            throw new Exception('The idea you are trying to select or un-select does not exist or it is not yours.');
        }

        $idea->selected = $selectedValue;
        $idea->save();
    }

    /**
     * Set idea important state
     *
     * @param int $userId User id.
     * @param int $ideaId Idea id.
     * @param string $importantState True or false.
     * @throws Exception If idea being changed is not found or not owned by user.
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
     *
     * @param int $userId User id.
     * @param int $ideaId Idea id.
     * @param string $urgentState True or false.
     * @throws Exception If idea being changed is not found or not owned by user.
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
     * Sets an idea as a Mission, updating it with given dateTodo, timeFrom and timeTill.
     * Note: these three parameters can be null..
     *
     * @param int $userId User id.
     * @param int $ideaId Idea id.
     * @param null|\DateTime $dateTodo Date to start the routine.
     * @param string $timeFrom Time from
     * @param string $timeTill Time till
     * @throws Exception If Mission being set is not found or not owned by user.
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

    /**
     * Sets an idea as a Routine, updating it with given dateStart, dateFinish, timeFrom and timeTill.
     * Note: dateStart, dateFinish, timeFrom and timeTill can be null.
     *
     * @param int $userId User id
     * @param int $ideaId Idea id
     * @param string $inputRoutineFrequencyDays Days of the week to apply this routine
     * @param string $inputRoutineFrequencyWeeks Every how many weeks this routine is to be applied
     * @param null|\DateTime $dateStart Date to start the routine
     * @param null|\DateTime $dateFinish Date to finish the routine
     * @param string $timeFrom Time from
     * @param string $timeTill Time till
     * @throws Exception If Routine being set is not found or not owned by user.
     */
    public function setRoutineDateTime($userId, $ideaId, $inputRoutineFrequencyDays, $inputRoutineFrequencyWeeks, $dateStart, $dateFinish, $timeFrom, $timeTill)
    {
        /**
         * @var Idea $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea which Date and/or Time you are trying to set does not exist or it is not yours.');
        }

        $routine = $this->convertIdeaToRoutine($idea);
        $routine->frequency_days = $inputRoutineFrequencyDays;
        $routine->frequency_weeks = $inputRoutineFrequencyWeeks;
        $routine->date_start = $dateStart;
        $routine->date_finish = $dateFinish;
        $routine->time_from = $timeFrom;
        $routine->time_till = $timeTill;
        $routine->save();
    }

    /**
     * Receives an idea whose type is unknown.
     * Converts it, if required, into a Mission and returns it.
     *
     * @param Idea $idea Idea to be returned as Mission.
     * @return Mission What is requested.
     * @throws Exception If idea's type is unknown.
     */
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

    /**
     * Receives an idea whose type is unknown.
     * Converts it, if required, into a Routine and returns it.
     *
     * @param Idea $idea Idea to be returned as Routine.
     * @return Routine What is requested.
     * @throws Exception If idea's type is unknown.
     */
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

    public function getActions($userId)
    {
        $this->generateActions($userId);

        $response = array();

        /**
         * @var Action[] $rawActionsArray
         */
        $rawActionsArray = Action::find('all', array(
                'conditions' => array(
                    'user_id = ? AND
                    date_todo IS NULL AND (
                        date_done IS NULL OR 
                            date_done BETWEEN DATE(NOW() - INTERVAL 1 DAY) AND DATE(NOW() + INTERVAL 1 DAY)
                        )
                    OR (
                        date_todo BETWEEN DATE(NOW() - INTERVAL 1 DAY) AND DATE(NOW() + INTERVAL 1 DAY)
                    )',
                    $userId
                ))
        );

        foreach ($rawActionsArray as $action) {
            $response[] = $action->toArray();
        }

        return $response;
    }

    public function generateActions()
    {

    }
}