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
use application\models\IdeaModel;
use engine\drivers\Exception;

class MindFlowService extends Service
{
    /**
     * Service constructor of MindFlow logic.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ideas request. Returns a list of all active ideas.
     *
     * @param int $userId User Id requesting ideas.
     * @param string $step Stage for which the list is for.
     * @return array
     */
    public function getIdeas($userId, $step)
    {
        $response = array();

        /**
         * @var \ActiveRecord\Model[] $ideas
         */
        $ideas = Idea::find('all', array('conditions' => array(
            'user_id' => $userId,
            'postponed' => false
        )));

        foreach ($ideas as $idea) {
            $arr = array();
            $arr['id'] = $idea->id;
            $arr['title'] = $idea->title;
            $arr['date_creation'] = $idea->date_creation->format('d/m/Y');

            switch ($step) {
                case 'ApplyTime':
                    $arr['date_todo'] = (is_null($idea->date_todo)) ? '' : $idea->date_todo->format('d/m/Y');
                    $arr['time_todo'] = (is_null($idea->time_todo)) ? '' : substr($idea->time_todo, 0, 5);
                    $arr['frequency'] = $idea->frequency;
                    break;
                case 'Prioritize':
                    $arr['priority'] = $idea->priority;
                    break;
            }

            $response[] = $arr;
        }
        return $response;
    }

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
         * @var \ActiveRecord\Model $idea
         */
        $idea = Idea::create(array(
            'user_id' => $userId,
            'title' => $title,
            'date_creation' => date('Y-m-d')
        ));

        return array('id' => $idea->id,
            'title' => $idea->title,
            'date_creation' => $idea->date_creation->format('d/m/Y'));
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
         * @var \ActiveRecord\Model $idea
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
         * @var \ActiveRecord\Model $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (null === $idea OR $userId != $idea->user_id) {
            throw new Exception('The idea you are trying to delete does not exist or it is not yours.');
        }

        $idea->delete();
    }

    /**
     * Postpone idea for tomorrow.
     * @param int $userId
     * @param int $ideaId
     * @throws Exception
     */
    public function postponeIdea($userId, $ideaId)
    {
        /**
         * @var \ActiveRecord\Model $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea you are trying to hold over does not exist or it is not yours.');
        }

        $idea->postponed = true;
        $idea->save();
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
         * @var \ActiveRecord\Model $idea
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
     * Prioritize idea.
     * @param int $userId
     * @param int $ideaId
     * @param int $ideaPriority
     * @throws Exception
     */
    public function prioritizeIdea($userId, $ideaId, $ideaPriority)
    {
        /**
         * @var \ActiveRecord\Model $idea
         */
        $idea = Idea::find_by_id($ideaId);

        if (is_null($idea) or $idea->user_id != $userId) {
            throw new Exception('The idea you are trying to hold over does not exist or it is not yours.');
        }

        $idea->priority = $ideaPriority;
        $idea->save();
    }
}