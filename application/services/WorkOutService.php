<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Work Out page's logic.
 * Date: 23/07/13 13:00
 */

namespace application\services;

use application\engine\Service;
use application\models\ActionModel;
use application\models\Idea;
use application\models\IdeaModel;
use engine\drivers\Exception;

class WorkOutService extends Service
{
    /**
     * Service constructor of Work Out logic.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Asynchronous request to get all current user ideas in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting ideas.
     * @param string (stepSelection / stepTiming / stepPrioritizing)
     * @return array
     */
    public function getIdeasForStep($userId, $step)
    {
        // Object response
        $response = array();

        // Getting Data from DB
        $ideas = Idea::find('all', array('conditions' => array(
            'postponed = 0 and user_id = ?', $userId
        )));
        
        $index = 0;
        foreach ($ideas as $idea) {
            /**
             * @var \ActiveRecord\Model $idea
             */
            $response[$index] = array(
                'id' => $idea->id,
                'title' => $idea->title,
                'date_creation' => $idea->date_creation->format('Y-m-d')
            );
            
            if ($step == 'stepApplyTime') {
                $response[$index]['date_todo'] = (is_null($idea->date_todo)) ? '' : $idea->date_todo->format('Y-m-d');
                $response[$index]['time_todo'] = (is_null($idea->time_todo)) ? '' : substr($idea->time_todo, 0, 5);
                $response[$index]['frequency'] = $idea->frequency;
            }
            
            if ($step == 'stepPrioritize') {
                $response[$index]['priority'] = $idea->priority;
            }
            
            $index++;
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
    public function createIdea($userId, $title)
    {
        $date_creation = date('Y-m-d');
        $newIdeaId = $this->_model->insert($userId, $title, $date_creation);

        return array(
            'id' => $newIdeaId,
            'title' => $title,
            'date_creation' => $date_creation);
    }

    /**
     * Edit Idea
     *
     * @param int $ideaId
     * @param int $userId
     * @param string $newTitle
     * @throws Exception
     */
    public function editIdea($ideaId, $userId, $newTitle)
    {
        $idea = $this->_model->selectById($ideaId, $userId);

        if ($idea === FALSE) {
            throw new Exception('The idea you are trying to modify does not exist or it is not yours.');
        }

        if (
            $newTitle == $idea['title']
        ) {
            throw new Exception('This edition request is not changing any idea data.');
        }

        $this->_model->update($ideaId, $userId, array(
            'title' => $newTitle
        ));
    }

    /**
     * Delete idea
     *
     * @param int $ideaId
     * @param int $userId
     * @throws Exception
     */
    public function deleteIdea($ideaId, $userId)
    {
        $idea = $this->_model->selectById($ideaId, $userId);

        if ($idea === FALSE) {
            throw new Exception('The idea you are trying to delete does not exist or it is not yours.');
        }

        $this->_model->delete($ideaId, $userId);
    }

    /**
     * Hold Over Idea
     *
     * @param int $ideaId
     * @param int $userId
     * @throws Exception
     */
    public function holdOverIdea($userId, $ideaId)
    {
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
     * @param int $ideaId Idea which time is being applied.
     * @param int $userId Current User
     * @param string $date Date in which this idea needs to be done. Empty string if not required.
     * @param string $time Time in which this idea needs to be done. Empty string if not required.
     * @param array $frequency Array of days that User wants to do this idea. Empty array if not required.
     * @throws Exception
     */
    public function applyTimeIdea($ideaId, $userId, $date, $time, $frequency)
    {
        // 1 - If all parameters are empty trigger exception.
        if (strlen($date) == 0 AND
            strlen($time) == 0 AND
            count($frequency) == 0
        ) {
            throw new Exception ('This request is not changing anything.');
        } else {
            if (strlen($date) == 0) {
                $date = NULL;
            }
            if (strlen($time) == 0) {
                $time = NULL;
            }
        }

        // 2 Turn Frequency array into frequency string
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

        // Updating Idea
        $this->_model->update($ideaId, $userId, array(
            'date_todo' => $date,
            'time_todo' => $time,
            'frequency' => $parsedFrequency
        ));

        echo $this->_model->db->getLastQuery();
    }

    /**
     * Sets Priority To Idea
     *
     * @param int $ideaId
     * @param int $userId
     * @param int $priority
     * @throws Exception
     */
    public function setPriorityToIdea($ideaId, $userId, $priority)
    {
        $idea = $this->_model->selectById($ideaId, $userId);

        if ($idea === FALSE) {
            throw new Exception('The idea you are trying to hold over does not exist or it is not yours.');
        }

        $this->_model->update($ideaId, $userId, array(
            'priority' => $priority
        ));
    }

    /**
     * Turns active ideas from user into actions.
     * @param $userId
     * @throws Exception
     */
    public function generateActionPlan($userId)
    {
        // Preparing Action model.
        $actionModel = new ActionModel;

        // Getting ideas
        $ideas = $this->_model->getUserActiveIdeas($userId);

        if (count($ideas) == 0) {
            throw new Exception('Without ideas you cannot make an action plan!');
        }

        $date_creation = date('Y-m-d');

        foreach ($ideas as $idea) {
            $actionModel->insert(
                $userId,
                $idea['title'],
                $date_creation,
                $idea['date_todo'],
                $idea['time_todo'],
                $idea['priority']
            );

            $this->_model->delete($idea['id'], $userId);
        }
    }
}