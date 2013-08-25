<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Index page's logic.
 * Date: 23/07/13 10:00
 */

namespace application\libraries;

use application\engine\Library;
use application\models\IdeaModel;
use application\models\UserModel;
use engine\Session;

class IndexLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var UserModel $_model
     */
    protected $_model;

    /**
     * Index Library Constructor.
     */
    public function __construct()
    {
        parent::__construct(new UserModel);
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
        $userData = $this->_model->selectUserForLogin($name, $password);

        if ($userData!== FALSE) {
            // User is logged in.

            // Setting Session parameters
            Session::set('isUserLoggedIn', true);
            Session::set('userId', $userData['id']);
            Session::set('userName', $userData['name']);
            Session::set('userRole', $userData['role']);

            // In case the User is logged a different day than the last time...
            if ($userData['last_login'] != date('Y-m-d'))
            {
                // Setting last login of User to today.
                $this->_model->update($userData['id'], array('last_login' => date('Y-m-d')));

                // Setting all postponed ideas of User to false.
                $ideaModel = new IdeaModel();
                $postponedIdeas = $ideaModel->getPostponedIdeas($userData['id']);
                foreach($postponedIdeas as $idea)
                {
                    $ideaModel->update($idea['id'], $userData['id'], array('postponed' => false));
                }
            }

            return TRUE;
        } else {
            return FALSE;
        }
    }
}