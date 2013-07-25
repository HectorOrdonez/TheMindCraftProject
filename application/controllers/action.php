<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Controller of the page Action.
 * Pending of Documentation.
 * Date: 25/07/13 01:30
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\ActionLibrary;
use engine\Session;

class action extends Controller
{
    /**
     * Defining $_library Library type.
     * @var ActionLibrary $_library
     */
    protected $_library;

    public function __construct()
    {
        parent::__construct(new ActionLibrary);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Action index page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'application/views/action/js/action.js');
        $this->_view->addLibrary('css', 'application/views/action/css/action.css');
        $this->_view->addChunk('action/index');
    }
}