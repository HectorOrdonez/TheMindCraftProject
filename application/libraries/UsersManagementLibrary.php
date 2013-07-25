<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Library that manages the UsersManagement page's logic.
 * Date: 25/07/13 01:30
 */

namespace application\libraries;

use application\engine\Library;
use application\models\UserModel;
use engine\Exception;

class UsersManagementLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var UserModel $_model
     */
    protected $_model;

    /**
     * Library constructor of UsersManagement logic.
     */
    public function __construct()
    {
        parent::__construct(new UserModel);
    }

    /**
     * Asynchronous request to get all Users in UsersManagement in an Object that JQuery Grid can understand.
     *
     * @param int $page Page requested
     * @param int $rows Amount of maximum rows the grid needs
     * @param string $sidx Column the list needs to be sorted with
     * @param string $sord (asc/desc) Direction of the sorting
     * @return \stdClass
     */
    public function getUsers($page, $rows, $sidx, $sord)
    {
        // Object response
        $response = new \stdClass();

        $totalRecords = ceil(count($this->_model->selectAll()) / $rows);

        // Defining the Start
        $start = $rows * $page - $rows;

        // Getting Data from DB
        $parameters = array(
            'sidx' => $sidx,
            'sord' => $sord,
            'start' => $start,
            'rows' => $rows
        );

        $result = $this->_model->getUsersList($parameters);

        // Defining parameters required
        $response->page = $page;
        $response->total = $totalRecords;
        $response->records = count($result);
        $response->users = array();

        foreach ($result as $user) {
            $response->users[] = array(
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role']
            );
        }

        return $response;
    }

    /**
     * Create user
     * /**
     * @param $name string
     * @param $password string
     * @param $role string
     */
    public function createUser($name, $password, $role)
    {
        $this->_model->insert($name, $password, $role);
    }

    /**
     * Edit user
     *
     * @param int $userId
     * @param string $newName
     * @param string $newRole
     * @throws Exception
     */
    public function editUser($userId, $newName, $newRole)
    {
        $user = $this->_model->selectById($userId);

        if ($user === FALSE) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newName == $user['name'] AND $newRole == $user['role']) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $this->_model->update($userId, array(
            'name' => $newName,
            'role' => $newRole
        ));
    }

    /**
     * Edit user password
     *
     * @param int $userId
     * @param string $newPassword
     * @throws Exception
     */
    public function editUserPassword($userId, $newPassword)
    {
        $user = $this->_model->selectById($userId);

        if ($user === FALSE) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        $this->_model->update($userId, array(
            'id' => $userId,
            'password' => $newPassword
        ));
    }

    /**
     * Delete user
     *
     * @param int $userId
     * @throws Exception
     */
    public function deleteUser($userId)
    {
        $user = $this->_model->selectById($userId);

        if ($user === FALSE) {
            throw new Exception('The User you are trying to delete does not exist.');
        }

        $this->_model->delete($userId);

    }
}