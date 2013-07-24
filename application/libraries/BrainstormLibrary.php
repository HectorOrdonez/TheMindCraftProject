<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Library that manages the Brainstorm page's logic.
 * Date: 23/07/13 13:00
 */

namespace application\libraries;

use application\engine\Library;
use application\models\IdeaModel;
use engine\Exception;

class BrainstormLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var IdeaModel $_model
     */
    protected $_model;

    /**
     * Library constructor of Brainstorm logic.
     */
    public function __construct()
    {
        parent::__construct(new IdeaModel());
    }

    /**
     * Asynchronous request to get all ideas in Brainstorm in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting ideas.
     * @param int $page Page requested
     * @param int $rows Amount of maximum rows the grid needs
     * @param string $sidx Column the list needs to be sorted with
     * @param string $sord (asc/desc) Direction of the sorting
     * @return \stdClass
     */
    public function getIdeas($userId, $page, $rows, $sidx, $sord)
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

        foreach ($result as $idea) {
            $response->ideas[] = array(
                'id' => $idea['id'],
                'title' => $idea['title'],
                'date_creation' => $idea['date_creation']
            );
        }

        return $response;
    }

    /**
     * Creates an idea related to given user.
     *
     * @param string $userId
     * @param string $title
     */
    public function createIdea($userId, $title)
    {
        $date_creation = date('Y-m-d');
        $this->_model->insert($userId, $title, $date_creation);
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
}