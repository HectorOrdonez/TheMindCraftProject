<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Library that manages the Index page's logic.
 * Date: 23/07/13 10:00
 */

namespace application\libraries;

use application\engine\Library;
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
        $result = $this->_model->selectUserForLogin($name, $password);

        if ($result !== FALSE) {
            Session::set('isUserLoggedIn', true);
            Session::set('userId', $result['id']);
            Session::set('userName', $result['name']);
            Session::set('userRole', $result['role']);
            return TRUE;
        } else {
            return FALSE;
        }
    }
}