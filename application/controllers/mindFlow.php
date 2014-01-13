<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * MindFlow page is the step manager. Offers the brainStorm, WorkOut and PerForm steps.
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
        // Table related libraries
        $this->_view->addLibrary('public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('public/css/helpers/gridElements/gridElements.css');

        // MindFlow libraries
        $this->_view->addLibrary('application/views/mindFlow/css/mindFlow.css');
        $this->_view->addLibrary('application/views/mindFlow/js/mindFlow.js');
        
        // Step related libraries
        $this->_view->addLibrary('application/views/brainStorm/js/brainStorm.js');
        $this->_view->addLibrary('application/views/workOut/js/selection.js');
        $this->_view->addLibrary('application/views/workOut/js/applyTime.js');
        $this->_view->addLibrary('application/views/workOut/js/prioritize.js');
        $this->_view->addLibrary('application/views/brainStorm/css/brainStorm.css');
        $this->_view->addLibrary('application/views/workOut/css/workOut.css');
        
        // Additional libraries
        $this->_view->addLibrary('public/css/external/jquery-ui-1.10.3.custom.css');
        $this->_view->addLibrary('public/js/external/jquery.transit.js');
        $this->_view->addLibrary('public/js/external/jquery-ui-1.10.3.custom.js');
        $this->_view->addLibrary('public/js/external/jquery-timepicker.js');
        
        $this->_view->setParameter('initStep', $step);
        $this->_view->addChunk('mindFlow/index');
        
    }
}