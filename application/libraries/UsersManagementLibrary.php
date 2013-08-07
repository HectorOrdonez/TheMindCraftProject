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
     * Asynchronous request to get all users.
     * @return \stdClass
     */
    public function getUsers()
    {
        $result = $this->_model->selectAll();

        return $result;
    }

    /**
     * Create user
     * /**
     * @param $name string
     * @return array Id and Name of new user.
     */
    public function createUser($name)
    {
        $newUserId = $this->_model->insert($name);

        return $this->_model->selectById($newUserId);
    }

    /**
     * Edit user name
     *
     * @param int $userId
     * @param string $newName
     * @throws Exception
     */
    public function editUserName($userId, $newName)
    {
        $user = $this->_model->selectById($userId);

        if ($user === FALSE) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newName == $user['name']) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $this->_model->update($userId, array('name' => $newName));
    }


    /**
     * Edit user role
     *
     * @param int $userId
     * @param string $newRole
     * @throws Exception
     */
    public function editUserRole($userId, $newRole)
    {
        $user = $this->_model->selectById($userId);

        if ($user === FALSE) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newRole == $user['role']) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $this->_model->update($userId, array('role' => $newRole));
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

        try {
            $this->_model->update($userId, array(
                'password' => $newPassword
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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