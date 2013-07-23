<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Date: 23/07/13 12:59
 */

namespace application\libraries;

use application\engine\Library;
use application\models\IdeaModel;
use engine\Exception;

class IdeaToActionLibrary extends Library
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
     * Asynchronous request to get all brainstormed ideas in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting ideas.
     * @param int $page Page requested
     * @param int $rows Amount of maximum rows the grid needs
     * @param string $sidx Column the list needs to be sorted with
     * @param string $sord (asc/desc) Direction of the sorting
     * @return \stdClass
     */
    public function getBrainstormedIdeas($userId, $page, $rows, $sidx, $sord)
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
                'title' => $idea['title']
            );
        }

        return $response;
    }
}