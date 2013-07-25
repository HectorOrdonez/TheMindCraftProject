<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Controller of the page UsersManagement.
 * Pending of Documentation.
 * Date: 25/07/13 01:30
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\UsersManagementLibrary;
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
        if ($logged == FALSE OR $role!= 'admin') {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * UsersManagement index page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'public/js/external/grid.locale-en.js');
        $this->_view->addLibrary('js', 'public/js/external/jquery.jqGrid.src.js');
        $this->_view->addLibrary('js', 'public/js/external/jquery-ui-1.10.3.custom.js');
        $this->_view->addLibrary('js', 'public/js/jqgridToolkit.js');
        $this->_view->addLibrary('js', 'application/views/usersManagement/js/usersManagement.js');

        $this->_view->addLibrary('css', 'public/css/jquery-ui-1.10.3.custom.css');
        $this->_view->addLibrary('css', 'public/css/ui.jqgrid.css');
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

        $form = new Form();
        $form
            ->requireItem('page') //Get the page requested
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('rows') // Get how many rows are required in the grid
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('sidx') // Get the column the list needs to be sorted with
            ->validate('Enum', array(
                'availableOptions' => array(
                    'id',
                    'name',
                    'role'
                )
            ))
            ->requireItem('sord') // Get the direction of the sorting
            ->validate('Enum', array(
                'availableOptions' => array(
                    'asc',
                    'desc'
                )
            ));

        $response = $this->_library->getUsers(
            $form->fetch('page'),
            (int)$form->fetch('rows'),
            $form->fetch('sidx'),
            $form->fetch('sord')
        );

        header("Content-type: application/json;charset=utf-8");
        echo json_encode($response);
    }

    /**
     * Asynchronous Jquery Grid request for adding a new user.
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
            ))
            ->requireItem('password')
            ->validate('String', array(
                'minLength' => 5,
                'maxLength' => 50,
            ))
            ->requireItem('role')
            ->validate('Enum', array(
                'availableOptions' => array(
                    'superadmin',
                    'admin',
                    'basic'
                )
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->createUser(
                $form->fetch('name'),
                $form->fetch('password'),
                $form->fetch('role')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous Jquery Grid request for editing a user.
     */
    public function editUser()
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
            ))
            ->requireItem('role')
            ->validate('Enum', array(
                'availableOptions' => array(
                    'superadmin',
                    'admin',
                    'basic'
                )
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->editUser(
                (int)$form->fetch('id'),
                $form->fetch('name'),
                $form->fetch('role')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous Jquery Grid request for editing User's password.
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
     * Asynchronous Jquery Grid request for deleting a user.
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