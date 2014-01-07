<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Perform.
 * Pending of Documentation.
 * Date: 25/07/13 01:30
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\PerformService;
use engine\drivers\Exception;
use engine\Form;
use engine\Session;

class perform extends Controller
{
    /**
     * Defining $_service Service type.
     * @var PerformService $_service
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new PerformService);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Perform index page.
     * Loads the required JS libraries of this page, together with language and CSS styles for the grid and the page.
     */
    public function index()
    {
        $this->_view->addLibrary('public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('application/views/action/js/action.js');
        $this->_view->addLibrary('application/views/action/css/action.css');

        $this->_view->addChunk('action/index');
    }

    /**
     * Asynchronous Jquery Grid request for filling up the pending actions grid.
     */
    public function getActions()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $response = $this->_library->getActions(
            Session::get('userId')
        );

        echo json_encode($response);
    }


    /**
     * Asynchronous Jquery Grid request for filling up the old actions grid.
     */
    public function getOldActions()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $response = $this->_library->getOldActions(
            Session::get('userId')
        );

        echo json_encode($response);
    }

    /**
     * Asynchronous Jquery Grid request for finishing an action.
     */
    public function finishAction()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->finishAction(
                $form->fetch('id'),
                Session::get('userId')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous Jquery Grid request for deleting an action.
     */
    public function deleteAction()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->deleteAction(
                $form->fetch('id'),
                Session::get('userId')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }
}