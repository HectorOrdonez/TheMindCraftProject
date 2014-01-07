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
use application\services\ProfileService;
use engine\Session;

class profile extends Controller
{
    /**
     * Defining $_service Service type.
     * @var ProfileService $_service
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new ProfileService);

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
        $this->_view->addLibrary('application/views/profile/js/profile.js');
        $this->_view->addLibrary('application/views/profile/css/profile.css');
        $this->_view->addChunk('profile/index');
    }
}