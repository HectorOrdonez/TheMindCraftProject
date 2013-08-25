<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Profile.
 * Pending of Documentation.
 * Date: 25/07/13 01:30
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\ProfileLibrary;
use engine\Session;

class profile extends Controller
{
    /**
     * Defining $_library Library type.
     * @var ProfileLibrary $_library
     */
    protected $_library;

    public function __construct()
    {
        parent::__construct(new ProfileLibrary);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Profile index page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'application/views/profile/js/profile.js');
        $this->_view->addLibrary('css', 'application/views/profile/css/profile.css');
        $this->_view->addChunk('profile/index');
    }
}