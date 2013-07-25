<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Controller of the page Settings.
 * Pending of Documentation.
 * Date: 25/07/13 01:30
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\SettingsLibrary;
use engine\Session;

class settings extends Controller
{
    /**
     * Defining $_library Library type.
     * @var SettingsLibrary $_library
     */
    protected $_library;

    public function __construct()
    {
        parent::__construct(new SettingsLibrary);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Settings index page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'application/views/settings/js/settings.js');
        $this->_view->addLibrary('css', 'application/views/settings/css/settings.css');
        $this->_view->addChunk('settings/index');
    }
}