<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Brainstorm page's logic.
 * Date: 23/07/13 13:00
 */

namespace application\services;

use application\engine\Service;
use application\models\Idea;
use application\models\IdeaModel;
use engine\drivers\Exception;

class BrainstormService extends Service
{
    /**
     * Service constructor of Brainstorm logic.
     */
    public function __construct()
    {
        parent::__construct();
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
        $ideas = Idea::find('all', array('conditions' => array(
            'user_id' => $userId
        )));

        foreach ($ideas as $idea) {
            $response[] = array(
                'id' => $idea->id,
                'title' => $idea->title,
                'date_creation' => $idea->date_creation->format('Y-m-d')
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
        $idea = Idea::create(array(
            'user_id' => $userId,
            'title' => $title,
            'date_creation' => date('Y-m-d')
        ));

        return array('id' => $idea->id,
            'title' => $idea->title,
            'date_creation' => $idea->date_creation->format('Y-m-d'));
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
        $idea = Idea::find_by_id($ideaId);

        if (null === $idea OR $userId != $idea->user_id) {
            throw new Exception('The idea you are trying to delete does not exist or it is not yours.');
        }
        
        $idea->delete();
    }
}