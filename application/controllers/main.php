<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Main.
 * This is just a welcoming page to the user.
 * Date: 23/07/13 10:30
 */

namespace application\controllers;

use application\engine\Controller;
use engine\Session;

class main extends Controller
{
    /**
     * Controller constructor for the Main page.
     */
    public function __construct()
    {
        parent::__construct();

        $logged = Session::get('isUserLoggedIn');
        if ($logged === FALSE) {
            header('location: ' . _SYSTEM_BASE_URL . 'index');
        }
    }

    /**
     * Main index page.
     */
    public function index()
    {
        $this->_view->addLibrary('css', 'application/views/main/css/main.css');

        $this->_view->addLibrary('js', 'application/views/main/js/main.js');
        
        $this->_view->addChunk('main/index');
        
        $this->_view->setParameter('userName', Session::get('userName'));
    }
}