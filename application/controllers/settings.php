<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Settings.
 * Pending of Documentation.
 * Date: 25/07/13 01:30
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\SettingsService;
use engine\Input;
use engine\drivers\Exceptions\RuleException;
use engine\Session;

class settings extends Controller
{
    /**
     * Defining $_service Service type.
     * @var SettingsService $_service
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new SettingsService);

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
        $this->_view->addLibrary('application/views/settings/js/settings.js');
        $this->_view->addLibrary('application/views/settings/css/settings.css');

        $this->_view->setParameter('currentUsername', Session::get('userName'));

        $this->_view->addChunk('settings/index');
    }

    /**
     * Asynchronous request for updating a user setting.
     */
    public function updateSetting()
    {
        try {
            $inputType = Input::build('Select', 'type')
                ->addRule('availableOptions', array('name', 'password'));
            $inputValue = Input::build('Text', 'newValue')
                ->addRule('minLength', 1)
                ->addRule('maxLength', 100);

            $inputType->validate();
            $inputValue->validate();
            
            $this->_service->updateSetting(Session::get('userId'), $inputType->getValue(), $inputValue->getValue());
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
        }
    }
}