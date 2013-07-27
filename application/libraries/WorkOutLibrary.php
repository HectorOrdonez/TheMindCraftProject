<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Library that manages the Work Out page's logic.
 * Date: 23/07/13 13:00
 */

namespace application\libraries;

use application\engine\Library;
use application\models\ActionModel;
use application\models\IdeaModel;
use engine\Exception;

class WorkOutLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var IdeaModel $_model
     */
    protected $_model;

    /**
     * Library constructor of Work Out logic.
     */
    public function __construct()
    {
        parent::__construct(new IdeaModel());
    }

    /**
     * Asynchronous request to get the ideas from the user in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting ideas.
     * @param string $step Work Out step requesting the ideas
     * @param int $page Page requested
     * @param int $rows Amount of maximum rows the grid needs
     * @param string $sidx Column the list needs to be sorted with
     * @param string $sord (asc/desc) Direction of the sorting
     * @return \stdClass
     */
    public function getIdeas($userId, $step, $page, $rows, $sidx, $sord)
    {
        // Object response
        $response = new \stdClass ();

        $totalRecords = ceil(count($this->_model->getAllUserIdeas($userId)) / $rows);

        // Defining the Start
        $start = $rows * $page - $rows;

        // Getting Data from DB
        $parameters = array(
            'user_id' => $userId,
            'sidx' => $sidx,
            'sord' => $sord,
            'start' => $start,
            'rows' => $rows
        );
        $result = $this->_model->getUserIdeasList($parameters);

        // Defining parameters required
        $response->page = $page;
        $response->total = $totalRecords;
        $response->records = count($result);
        $response->ideas = array();

        // Getting the fields that this step needs
        $fields = $this->_getRequiredFields($step);

        foreach ($result as $idea) {
            $newIdea = array();

            foreach($fields as $i=>$field)
            {
                $newIdea[$field] = $idea[$field];
            }
            $response->ideas[] = $newIdea;
        }

        return $response;
    }

    /**
     * Returns the list of fields that this step needs for its grid.
     * @param string $step (stepSelection / stepTiming / stepPrioritizing)
     * @return array List of fields
     */
    private function _getRequiredFields($step)
    {
        $requiredFields = array();
        $requiredFields[] = 'id';
        $requiredFields[] = 'title';

        switch ($step)
        {
            case 'stepSelection':
                $requiredFields[] = 'date_creation';
                break;
            case 'stepTiming':
                $requiredFields[] = 'frequency';
                $requiredFields[] = 'time_todo';
                break;
            case 'stepPrioritizing':
                break;
        }

        return $requiredFields;
    }

    /**
     * Edit Idea
     *
     * @param int $ideaId
     * @param int $userId
     * @param string $holdOverDate
     * @throws Exception
     */
    public function holdOverIdea($ideaId, $userId, $holdOverDate)
    {
        $idea = $this->_model->selectById($ideaId, $userId);

        if ($idea === FALSE) {
            throw new Exception('The idea you are trying to hold over does not exist or it is not yours.');
        }

        if (
            $holdOverDate == $idea['date_todo']
        ) {
            throw new Exception('This edition request is not changing any idea data.');
        }

        $this->_model->update($ideaId, $userId, array(
            'date_todo' => $holdOverDate
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

    public function generateActionPlan($userId)
    {
        // Preparing Action model.
        $actionModel = new ActionModel;

        // Getting ideas
        $ideas = $this->_model->getAllUserIdeas($userId);

        if (count($ideas) == 0) {
            throw new Exception('Without ideas you cannot make an action plan!');
        }

        $date_creation = date('Y-m-d');

        foreach ($ideas as $idea) {
            $actionModel->insert($userId, $idea['title'], $date_creation);

            $this->_model->delete($idea['id'], $userId);
        }
    }
}