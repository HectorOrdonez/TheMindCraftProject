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
    const DEFAULT_USER_STATE = 'pending';
    
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
        $result = array();

        /**
         * @var User[] $users
         */
        $users = User::find('all');

        foreach ($users as $user) {
            $result[] = array(
                'id' => $user->id,
                'username' => $user->username,
                'mail' => $user->mail,
                'role' => $user->role,
                'state' => $user->state,
                'last_login' => (is_null($user->last_login) ? '' : $user->last_login->format('d/m/Y')));
        }

        return $result;
    }

    /**
     * Asynchronous request to get the amount of users whose state is pending.
     * @return int Amount
     */
    public function countPendingUsers()
    {
        $amount = User::count(array('conditions' => "state = 'pending'"));

        return $amount;
    }

    /**
     * Create user
     * /**
     * @param $username string
     * @return array Id and Name of new user.
     */
    public function createUser($username)
    {
        $user = User::create(array('username' => $username));

        return array(
            'id' => $user->id,
            'username' => $user->username,
            'mail' => $user->mail,
            'role' => $user->role,
            'state' => self::DEFAULT_USER_STATE,
            'last_login' => ''
        );
    }

    /**
     * Edit Username
     *
     * @param int $userId
     * @param string $newUsername
     * @throws Exception
     */
    public function editUsername($userId, $newUsername)
    {
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);

        if (is_null($user)) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newUsername == $user->username) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $user->username = $newUsername;
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
     * Edit user state
     *
     * @param int $userId
     * @param string $newState
     * @throws Exception
     */
    public function editUserState($userId, $newState)
    {
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);

        if (is_null($user)) {
            throw new Exception('The User you are trying to modify does not exist.');
        }

        if ($newState == $user->state) {
            throw new Exception('This edition request is not changing any User data.');
        }

        $user->state = $newState;
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