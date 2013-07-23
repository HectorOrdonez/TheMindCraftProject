<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Date: 11/06/13 11:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\IndexLibrary;
use engine\Form;
use engine\Session;

class Index extends Controller
{
    /**
     * Index constructor.
     * Verifies that the User is not logged in and, if so, redirects to Dashboard.
     */
    public function __construct()
    {
        parent::__construct(new IndexLibrary);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == TRUE) {
            header('location: ' . _SYSTEM_BASE_URL . 'main');
        }
        $this->_view->addLibrary('js', 'application/views/index/js/index.js');
        $this->_view->addLibrary('css', 'application/views/index/css/index.css');
    }

    public function index()
    {
        $this->_view->addChunk('index/index');
    }

    public function login()
    {
        // Validation
        $form = new Form;
        $form
            ->requireItem('username')
            ->validate('String', array(
                'minLength' => 3,
                'maxLength' => 50
            ))
            ->requireItem('password')
            ->validate('String', array(
                'minLength' => 3,
                'maxLength' => 50
            ));

        // Logic
        if (sizeof($form->getErrors()) == 0) {
            $login = $this->_library->login($form->fetch('username'), $form->fetch('password'));

            if ($login === TRUE) {
                header('location: ' . _SYSTEM_BASE_URL . 'index');
            } else {
                $this->_view->setParameter('loginError', 'Wrong username or Password.');
                $this->_view->addChunk('index/loginError');
                $this->_view->addChunk('index/index');
            }
        } else {
            $this->_view->setParameter('errors', $form->getErrors());
            $this->_view->addChunk('index/index');
            $this->_view->addChunk('index/inputErrors');
        }
    }

    public function logout()
    {
        Session::destroy();
        header('location: ' . _SYSTEM_BASE_URL . 'index');
    }
}