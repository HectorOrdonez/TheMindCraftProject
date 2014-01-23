<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Index page's logic.
 * Date: 23/07/13 10:00
 */

namespace application\services;

use application\engine\Service;
use application\models\Idea;
use application\models\IdeaModel;
use application\models\User;
use application\models\UserModel;
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

        if ('inactive' == $user->state) {
            throw new Exception('User is not active. Try again later.', Exception::GENERAL_EXCEPTION, EXCEPTION_LOGIN_USER_NOT_ACTIVE);
        }

        // Everything is alright.

        // Setting Session parameters
        Session::set('isUserLoggedIn', true);
        Session::set('userId', $user->id);
        Session::set('userName', $user->username);
        Session::set('userRole', $user->role);

        // In case the User is logged a different day than the last time...
        if ($user->last_login != date('Y-m-d')) {
            // Setting last login of User to today.
            $user->last_login = date('Y-m-d');
            $user->save();

            $this->unPostpone($user->id);
        }
    }

    /**
     * When a User logs in a day after, its postponed ideas are set to not-postponed.
     * @param $userId
     */
    private function unPostpone($userId)
    {

        // Setting all postponed ideas of User to false.
        /**
         * @var \ActiveRecord\Model[] $ideas
         */
        $ideas = Idea::find('all', array('conditions' => array(
            'postponed = true',
            "user_id = {$userId}"
        )));

        foreach ($ideas as $idea) {
            $idea->postponed = false;
            $idea->save();
        }
    }
}