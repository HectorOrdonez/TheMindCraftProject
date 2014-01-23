<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Index.
 * This is the main page of The Mindcraft Project, and displays a login panel that the User needs to fulfill if he or she wants to have access to the website.
 * Date: 11/06/13 11:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\IndexService;
use engine\Form;
use engine\Input;
use engine\Session;
use engine\drivers\Exception;

class index extends Controller
{
    /**
     * Defining $_service Service type.
     * @var IndexService $_service
     */
    protected $_service;

    /**
     * Index constructor.
     * Verifies that the User is not logged in and, if so, redirects to Dashboard.
     */
    public function __construct()
    {
        parent::__construct(new IndexService);
        $this->_view->addLibrary('application/views/index/js/index.js');
        $this->_view->addLibrary('application/views/index/css/index.css');
    }

    /**
     * Index index page.
     */
    public function index()
    {
        $logged = Session::get('isUserLoggedIn');
        if ($logged === TRUE) {
            header('location: ' . _SYSTEM_BASE_URL . 'main');
            exit;
        }
        $this->_view->addChunk('index/index');
    }

    /**
     * Login verifier; checks if the user input is valid and, if so, redirects to the starting page for users.
     */
    public function login()
    {
        $logged = Session::get('isUserLoggedIn');
        if ($logged === TRUE) {
            header('location: ' . _SYSTEM_BASE_URL . 'main');
            exit;
        }

        // Validation
        $form = new Form;
        $form->addInput(
            Input::build('Text', 'username')
                ->addRule('minLength', 3)
                ->addRule('maxLength', 50)
        );
        $form->addInput(
            Input::build('Text', 'password')
                ->addRule('minLength', 3)
                ->addRule('maxLength', 50)
        );

        // Logic
        $wrongInputs = $form->getValidationErrors();
        if (false !== $wrongInputs) {
            $errorArray = array();
            foreach ($wrongInputs as $input) {
                $errorArray[$input->getFieldName()] = $input->getError()->getMessage();
            }
            header('HTTP/1.1 400 Can not login with these parameters.');
            header('Content-Type: application/json');
            print json_encode($errorArray);
            return;
        }

        try {
            $this->_service->login($form->getInput('username')->getValue(), $form->getInput('password')->getValue());
        } catch (Exception $e) {
            if (EXCEPTION_LOGIN_USER_NOT_ACTIVE === $e->getCode() OR
                EXCEPTION_LOGIN_FAILED === $e->getCode()
            ) {
                header("HTTP/1.1 401 " . $e->getMessage());
                print $e->getMessage();
            } else {
                header("HTTP/1.1 500 " . $e->getMessage());
                print ('Unknown error.');
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Unknown error.');
            print ('Unknown error.');
        }
    }

    /**
     * Logout page. Destroys the user session and re
     */
    public function logout()
    {
        Session::destroy();
        header('location: ' . _SYSTEM_BASE_URL . 'index');
        exit;
    }
}