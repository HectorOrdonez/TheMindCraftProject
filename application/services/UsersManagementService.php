<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the UsersManagement page's logic.
 * Date: 25/07/13 01:30
 */

namespace application\services;

use application\engine\Service;
use application\models\User;
use engine\Encrypter;
use engine\drivers\Exception;

class UsersManagementService extends Service
{
    /**
     * Service constructor of UsersManagement logic.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Asynchronous request to get all users.
     * @return \stdClass
     */
    public function getUsers()
    {
        /**
         * @var User[] $users
         */
        $users = User::find('all');

        $result = array();
        foreach ($users as $user) {
            $result[] = array(
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'last_login' => (is_null($user->last_login)? '' : $user->last_login->format('Y-m-d')));
        }

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
        $user = User::create(array('name' => $name));

        return $user->id;
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
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);
        
        if (is_null($user)) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newName == $user->name) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $user->name = $newName;
        $user->save();
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
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);

        if (is_null($user)) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newRole == $user->role) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $user->role = $newRole;
        $user->save();
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
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);

        if (is_null($user)) {
            throw new Exception('The User you are trying to modify does not exist.');
        }
        
        $user->password = Encrypter::encrypt($newPassword);
        $user->save();
    }

    /**
     * Delete user
     *
     * @param int $userId
     * @throws Exception
     */
    public function deleteUser($userId)
    {
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);
        $user->delete();

    }
}