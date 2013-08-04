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
use engine\Exception;
use engine\Form;
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
     * Loads the required JS libraries of this page, together with language and CSS styles for the grid and the page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('css', 'public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('js', 'application/views/action/js/action.js');
        $this->_view->addLibrary('css', 'application/views/action/css/action.css');

        $this->_view->addChunk('action/index');
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
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
     * Asynchronous Jquery Grid request for completing (deleting) an action.
     */
    public function completeAction()
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