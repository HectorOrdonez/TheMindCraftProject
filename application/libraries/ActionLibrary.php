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
     * @return \stdClass
     */
    public function getActions($userId)
    {
        // Object response
        $response = array();

        $result = $this->_model->getAllUserActions($userId);

        // Defining parameters required

        foreach ($result as $action) {
            $response[] = array(
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