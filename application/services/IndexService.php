<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Service that manages the Index page's logic.
 * Date: 23/07/13 10:00
 */

namespace application\services;

use application\engine\Service;
use application\models\User;
use engine\Encrypter;
use engine\Session;
use engine\drivers\Exception;

class IndexService extends Service
{
    /**
     * Index Service constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Receives a name and a password. Using the User Model, verifies if the name and password are correct.
     * If so, saves in Session the relevant data.
     * @param string $username
     * @param string $password
     * @throws Exception
     */
    public function login($username, $password)
    {
        $user = User::find_by_username($username);

        if (is_null($user) OR (!Encrypter::verify($password, $user->password))) {
            throw new Exception('User does not exist or password does not match.', Exception::GENERAL_EXCEPTION, EXCEPTION_LOGIN_FAILED);
        }

        if ('active' != $user->state) {
            throw new Exception('User is not active. Try again later.', Exception::GENERAL_EXCEPTION, EXCEPTION_LOGIN_USER_NOT_ACTIVE);
        }

        // Setting Session parameters
        Session::set('isUserLoggedIn', true);
        Session::set('userId', $user->id);
        Session::set('userName', $user->username);
        Session::set('userRole', $user->role);
    }
}