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
     * @param string $name
     * @param string $password
     * @return bool If User is now logged in or not.
     */
    public function login($name, $password)
    {
        $user = User::find_by_name($name);

        if (is_null($user))
        {
            return false;
        }
        
        if (Encrypter::verify($password, $user->password))
        {
            // User is logged in.

            // Setting Session parameters
            Session::set('isUserLoggedIn', true);
            Session::set('userId', $user->id);
            Session::set('userName', $user->name);
            Session::set('userRole', $user->role);

            // In case the User is logged a different day than the last time...
            if ($user->last_login != date('Y-m-d'))
            {
                // Setting last login of User to today.
                $user->last_login = date('Y-m-d');
                $user->save();
                
                // Setting all postponed ideas of User to false.
                $ideas = Idea::find('all', array('conditions'=> array('postponed = true')));
                
                foreach($ideas as $idea)
                {
                    $idea->postponed = false;
                    $idea->save();
                }
            }

            return true;
        }
        
        return false;
    }
}