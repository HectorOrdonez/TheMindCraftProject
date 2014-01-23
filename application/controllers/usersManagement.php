<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page UsersManagement.
 * Allows the management of the Users; creating them, editing their names, roles and password and deleting them if
 * needed.
 * Notice that a User can not delete him or herself.
 * Date: 25/07/13 01:30
 *
 * Updated: 07/08/2013, Hector Ordonez
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\UsersManagementService;
use engine\Input;
use engine\drivers\Exception;
use engine\drivers\Exceptions\RuleException;
use engine\Session;

class usersManagement extends Controller
{
    /**
     * Defining $_service Service type.
     * @var UsersManagementService $_service
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new UsersManagementService);

        $logged = Session::get('isUserLoggedIn');
        $role = Session::get('userRole');
        if ($logged == FALSE OR $role != 'admin') {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * UsersManagement index page.
     */
    public function index()
    {
        $this->_view->addLibrary('public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('application/views/usersManagement/js/usersManagement.js');
        $this->_view->addLibrary('application/views/usersManagement/css/usersManagement.css');

        $this->_view->addChunk('usersManagement/index');
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     */
    public function getUsers()
    {
        $response = $this->_service->getUsers();

        print json_encode($response);
    }

    /**
     * Asynchronous request for adding a new user.
     *
     * Notice that the only parameters required is the name. The role and password of the user needs to be set after
     * its creation.
     *
     * Parameters required by post:
     * username - The username that this new user will have.
     */
    public function createUser()
    {
        try {
            $inputName = Input::build('Text', 'username')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 50);
            $inputName->validate();

            $newUserId = $this->_service->createUser($inputName->getValue());
            print json_encode($newUserId);
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        } catch (Exception $ex) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $ex->getMessage());
        }
    }

    /**
     * Asynchronous request for editing a user's name.
     *
     * Parameters required by post:
     * id - User id to delete.
     * username - New username
     */
    public function editUsername()
    {
        try {
            $inputId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputUsername = Input::build('Text', 'username')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 50);

            $inputId->validate();
            $inputUsername->validate();

            $this->_service->editUserName($inputId->getValue(), $inputUsername->getValue());
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        } catch (Exception $ex) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $ex->getMessage());
        }
    }

    /**
     * Asynchronous request for editing a user's role.
     *
     * Parameters required by post:
     * id - User id to delete.
     * role - New user role
     */
    public function editUserRole()
    {
        try {
            $inputId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputRole = Input::build('Select', 'role')->addRule('availableOptions', array('admin', 'basic'));

            $inputId->validate();
            $inputRole->validate();

            $this->_service->editUserRole($inputId->getValue(), $inputRole->getValue());
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        } catch (Exception $ex) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $ex->getMessage());
        }
    }

    /**
     * Asynchronous request for editing a user's state.
     *
     * Parameters required by post:
     * id - User id to delete.
     * state - New user state
     */
    public function editUserState()
    {
        try {
            $inputId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputState = Input::build('Select', 'state')->addRule('availableOptions', array('active', 'inactive'));

            $inputId->validate();
            $inputState->validate();

            $this->_service->editUserState($inputId->getValue(), $inputState->getValue());
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        } catch (Exception $ex) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $ex->getMessage());
        }
    }

    /**
     * Asynchronous request for editing User's password.
     *
     * Parameters required by post:
     * id - User Id to delete.
     * password - User password to replace the previous one.
     */
    public function editUserPassword()
    {
        try {
            $inputId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputPass = Input::build('Text', 'password')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 50);

            $inputId->validate();
            $inputPass->validate();

            $this->_service->editUserPassword($inputId->getValue(), $inputPass->getValue());
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        } catch (Exception $ex) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $ex->getMessage());
        }
    }

    /**
     * Asynchronous request for deleting a user.
     *
     * Parameters required by post:
     * id - User Id to delete.
     */
    public function deleteUser()
    {
        try {
            $inputId = Input::build('Number', 'id')
                ->addRule('isInt');

            $inputId->validate();

            $this->_service->deleteUser($inputId->getValue());
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        } catch (Exception $ex) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $ex->getMessage());
        }
    }
}