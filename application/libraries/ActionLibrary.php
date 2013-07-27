<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Library that manages the Action page's logic.
 * Date: 25/07/13 01:30
 */

namespace application\libraries;

use application\engine\Library;
use application\models\ActionModel;
use engine\Exception;

class ActionLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var ActionModel $_model
     */
    protected $_model;

    /**
     * Library constructor of Action logic.
     */
    public function __construct()
    {
        parent::__construct(new ActionModel);
    }

    /**
     * Asynchronous request to get all actions from user in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting actions.
     * @param int $page Page requested
     * @param int $rows Amount of maximum rows the grid needs
     * @param string $sidx Column the list needs to be sorted with
     * @param string $sord (asc/desc) Direction of the sorting
     * @return \stdClass
     */
    public function getActions($userId, $page, $rows, $sidx, $sord)
    {
        // Object response
        $response = new \stdClass ();

        $totalRecords = ceil(count($this->_model->getAllUserActions($userId)) / $rows);

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
        $result = $this->_model->getUserActionsList($parameters);

        // Defining parameters required
        $response->page = $page;
        $response->total = $totalRecords;
        $response->records = count($result);
        $response->ideas = array();

        foreach ($result as $action) {
            $response->actions[] = array(
                'id' => $action['id'],
                'title' => $action['title'],
                'date_creation' => $action['date_creation']
            );
        }

        return $response;
    }

    /**
     * Delete action
     *
     * @param int $actionId
     * @param int $userId
     * @throws Exception
     */
    public function deleteAction($actionId, $userId)
    {
        $idea = $this->_model->selectById($actionId, $userId);

        if ($idea === FALSE) {
            throw new Exception('The action you are trying to complete does not exist or it is not yours.');
        }

        $this->_model->delete($actionId, $userId);

    }
}