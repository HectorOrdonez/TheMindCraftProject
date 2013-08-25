<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Perform page's logic.
 * Date: 25/07/13 01:30
 */

namespace application\libraries;

use application\engine\Library;
use application\models\ActionModel;
use engine\Exception;

class PerformLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var ActionModel $_model
     */
    protected $_model;
    static private $_MAX_OLD_ACTIONS = 100;

    /**
     * Library constructor of Action logic.
     */
    public function __construct()
    {
        parent::__construct(new ActionModel);
    }

    /**
     * Asynchronous request to get all pending actions from user in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting actions.
     * @return \stdClass
     */
    public function getActions($userId)
    {
        // Object response
        $response = array();

        $result = $this->_model->getUserPendingActions($userId, 100);

        // Defining parameters required

        foreach ($result as $action) {
            $response[] = array(
                'id' => $action['id'],
                'title' => $action['title'],
                'priority' => $action['priority'],
                'date_creation' => $action['date_creation']
            );
        }

        return $response;
    }

    /**
     * Asynchronous request to get all old actions from user in an Object that JQuery Grid can understand.
     *
     * @param int $userId User Id requesting actions.
     * @return \stdClass
     */
    public function getOldActions($userId)
    {
        // Object response
        $response = array();

        $result = $this->_model->getUserFinishedActions($userId, self::$_MAX_OLD_ACTIONS);

        // Defining parameters required

        foreach ($result as $action) {
            $response[] = array(
                'id' => $action['id'],
                'title' => $action['title']
            );
        }

        return $response;
    }

    /**
     * Finish action
     *
     * @param int $actionId
     * @param int $userId
     * @throws Exception
     */
    public function finishAction($actionId, $userId)
    {
        $idea = $this->_model->selectById($actionId, $userId);

        if ($idea === FALSE) {
            throw new Exception('The action you are trying to finish does not exist or it is not yours.');
        }

        $date_done = date('Y-m-d');

        $this->_model->update($actionId, $userId, array(
            'date_done' => $date_done
        ));
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
            throw new Exception('The action you are trying to delete does not exist or it is not yours.');
        }

        $this->_model->delete($actionId, $userId);
    }
}