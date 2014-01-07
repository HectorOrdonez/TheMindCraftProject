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
use engine\Session;

class signUp extends Controller
{
    /**
     * Defining $_service Service type.
     * @var null $_library
     * @todo Assign library to the controller.
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct();

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
        $this->_view->addChunk('signUp/index');
    }
}