<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: 
 * Date: 23/07/13 10:26
 */

namespace application\controllers;

use application\engine\Controller;
use engine\Session;

class main extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'index');
        }
    }

    public function index()
    {
        $this->_view->addLibrary('css', 'application/views/main/css/main.css');

        $this->_view->addChunk('main/index');
    }
}