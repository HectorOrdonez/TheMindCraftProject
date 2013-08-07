<?php
/**
 * Project: Selfology
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
use application\libraries\UsersManagementLibrary;
use engine\Exception;
use engine\Form;
use engine\Session;

class usersManagement extends Controller
{
    /**
     * Defining $_library Library type.
     * @var UsersManagementLibrary $_library
     */
    protected $_library;

    public function __construct()
    {
        parent::__construct(new UsersManagementLibrary);

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
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('css', 'public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('js', 'application/views/usersManagement/js/usersManagement.js');
        $this->_view->addLibrary('css', 'application/views/usersManagement/css/usersManagement.css');

        $this->_view->addChunk('usersManagement/index');
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     */
    public function getUsers()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $response = $this->_library->getUsers();

        echo json_encode($response);
    }

    /**
     * Asynchronous request for adding a new user.
     *
     * Notice that the only parameters required is the name. The role and password of the user needs to be set after
     * its creation.
     *
     * Parameters required by post:
     * name - The name that this new user will have.
     */
    public function createUser()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $form = new Form();
        $form
            ->requireItem('name')
            ->validate('String', array(
                'minLength' => 5,
                'maxLength' => 50,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $createdUser = $this->_library->createUser($form->fetch('name'));
            echo json_encode($createdUser);
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous request for editing a user's name.
     *
     * Parameters required by post:
     * id - User id to delete.
     * name - New user name
     */
    public function editUserName()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('name')
            ->validate('String', array(
                'minLength' => 5,
                'maxLength' => 50,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->editUserName(
                (int)$form->fetch('id'),
                $form->fetch('name')
            );

        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
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
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('role')
            ->validate('Enum', array(
                'availableOptions' => array(
                    'admin',
                    'basic'
                ),
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->editUserRole(
                (int)$form->fetch('id'),
                $form->fetch('role')
            );

        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
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
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('password')
            ->validate('String', array(
                'minLength' => 5,
                'maxLength' => 50,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . 'Input password does not pass validation.');
        }

        // Executing
        try {
            $this->_library->editUserPassword(
                (int)$form->fetch('id'),
                $form->fetch('password')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
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
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        if ($form->fetch('id') == Session::get('userId')) {
            header("HTTP/1.1 400 " . 'Why do you want to commit suicide :( ???');
            exit;
        }

        // Executing
        try {
            $this->_library->deleteUser(
                $form->fetch('id')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }
}