<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page SignUp.
 * This page provides the user with a tool to sign up into The Mindcraft Project.
 *
 * Date: 22/11/13 20:00
 * @todo Add functionality. This is just a "In construction" page.
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\SignUpService;
use engine\Form;
use engine\Input;
use engine\Session;
use engine\drivers\Exception;

class signUp extends Controller
{
    /**
     * Defining $_service Service type.
     * @var SignUpService $_library
     * @todo Assign library to the controller.
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new SignUpService);

        $logged = Session::get('isUserLoggedIn');
        if ($logged === TRUE) {
            header('location: ' . _SYSTEM_BASE_URL . 'main');
            exit;
        }
    }

    /**
     * SignUp index page.
     * In Construction.
     */
    public function index()
    {
        $this->_view->addLibrary('application/views/signUp/js/signUp.js');
        $this->_view->addLibrary('application/views/signUp/css/signUp.css');
        $this->_view->addChunk('signUp/index');
    }

    /**
     * SignUp method to registering a new User.
     * At this stage the User creation is verified manually by the Admins (Zuzanna and Hector (me :D) ).
     */
    public function signUp()
    {
        // Validation
        $form = new Form();
        $form->addInput(
            Input::build('Mail', 'mail')
                ->addRule('maxLength', 50)
        );
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
            header('HTTP/1.1 400 Can not sign up with these parameters.');
            header('Content-Type: application/json');
            print json_encode($errorArray);
            return;
        }

        try {
            $this->_service->signUp($form->getInput('mail')->getValue(), $form->getInput('username')->getValue(), $form->getInput('password')->getValue());
        } catch (Exception $e) {
            if (EXCEPTION_SIGNUP_USERNAME_IN_USE === $e->getCode()) {
                header("HTTP/1.1 400 " . $e->getMessage());
                header('Content-Type: application/json');
                print json_encode(array('username' => $e->getMessage()));
            } else if (EXCEPTION_SIGNUP_MAIL_IN_USE === $e->getCode()) {
                header("HTTP/1.1 400 " . $e->getMessage());
                header('Content-Type: application/json');
                print json_encode(array('mail' => $e->getMessage()));
            } else {
                header("HTTP/1.1 500 " . $e->getMessage());
                print ('Unknown error.');
            }
        }
    }
}