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
use engine\Exception;
use engine\Form;
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

        $this->_view->setParameter('currentUsername', Session::get('userName'));

        $this->_view->addChunk('settings/index');
    }

    /**
     * Asynchronous request for updating a user setting.
     */
    public function updateSetting()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $form = new Form();
        $form
            ->requireItem('type') //Get the type of Setting to change
            ->validate('Enum', array(
                'availableOptions' => array(
                    'name',
                    'password'
                )
            ))
            ->requireItem('newValue') // Get the new value for the Setting
            ->validate('String', array(
                'minLength' => 1,
                'maxLength' => 100,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->updateSetting(
                Session::get('userId'),
                $form->fetch('type'),
                $form->fetch('newValue')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . $e->getMessage());
            exit;
        }
    }
}