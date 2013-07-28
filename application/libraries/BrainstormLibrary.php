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
     * @return \stdClass
     */
    public function getIdeas($userId)
    {
        // Object response
        $response = array();

        // Getting Data from DB
        $result = $this->_model->getAllUserIdeas($userId);

        foreach ($result as $idea) {
            $response[] = array(
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
     * @return array
     */
    public function createIdea($userId, $title)
    {
        $date_creation = date('Y-m-d');
        $this->_model->insert($userId, $title, $date_creation);

        return array(
            'title'=>$title,
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
}