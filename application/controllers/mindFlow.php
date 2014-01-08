<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * MindFlow page is the step manager. Offers the BrainStorm, WorkOut and PerForm steps.
 * Date: 8/01/14 15:00
 */

namespace application\controllers;

use application\engine\Controller;
use engine\Session;

/**
 * Class mindFlow
 * @package application\controllers
 */
class mindFlow extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $logged = Session::get('isUserLoggedIn');
        if ($logged === FALSE) {
            header('location: ' . _SYSTEM_BASE_URL . 'index');
        }
    }

    /**
     * MindFlow index page.
     */
    public function index($step = 'step1')
    {
        $this->_view->addLibrary('application/views/mindFlow/css/mindFlow.css');
        $this->_view->addLibrary('application/views/mindFlow/js/mindFlow.js');
        
        $this->_view->addLibrary('public/js/external/jquery.transit.js');

        $this->_view->setParameter('initStep', $step);
        $this->_view->addChunk('mindFlow/index');
        
    }
}