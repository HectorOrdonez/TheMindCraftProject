<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: 
 * Date: 23/07/13 12:56
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\BrainstormLibrary;
use engine\Form;
use engine\Session;

class brainstorm extends Controller
{
    /**
     * Defining $_library Library type.
     * @var BrainstormLibrary $_library
     */
    protected $_library;

    /**
     * Controller constructor for the Brainstorm page.
     */
    public function __construct()
    {
        parent::__construct(new BrainstormLibrary());

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Brainstorm index page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'public/js/external/grid.locale-en.js');
        $this->_view->addLibrary('js', 'public/js/external/jquery.jqGrid.src.js');
        $this->_view->addLibrary('js', 'public/js/external/jquery-ui-1.10.3.custom.js');
        $this->_view->addLibrary('js', 'public/js/jqgridToolkit.js');
        $this->_view->addLibrary('js', 'application/views/brainstorm/js/brainstorm.js');

        $this->_view->addLibrary('css', 'public/css/jquery-ui-1.10.3.custom.css');
        $this->_view->addLibrary('css', 'public/css/ui.jqgrid.css');
        $this->_view->addLibrary('css', 'application/views/brainstorm/css/brainstorm.css');

        $this->_view->addChunk('brainstorm/index');
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     */
    public function getIdeas()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $form = new Form();
        $form
            ->requireItem('page') //Get the page requested
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('rows') // Get how many rows are required in the grid
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('sidx') // Get the column the list needs to be sorted with
            ->validate('Enum', array(
                'availableOptions' => array(
                    'id',
                    'name',
                    'role'
                )
            ))
            ->requireItem('sord') // Get the direction of the sorting
            ->validate('Enum', array(
                'availableOptions' => array(
                    'asc',
                    'desc'
                )
            ));

        $response = $this->_library->getIdeas(
            Session::get('userId'),
            $form->fetch('page'),
            (int)$form->fetch('rows'),
            $form->fetch('sidx'),
            $form->fetch('sord')
        );

        header("Content-type: application/json;charset=utf-8");
        echo json_encode($response);
    }

    /**
     * Asynchronous Jquery Grid request for adding a new idea.
     */
    public function createIdea()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $form = new Form();
        $form
            ->requireItem('title')
            ->validate('String', array(
                'minLength' => 5,
                'maxLength' => 500,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->createIdea(
                Session::get('userId'),
                $form->fetch('title')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous Jquery Grid request for editing an idea.
     */
    public function editIdea()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        // Validating
        $form = new Form();
        $form
            ->requireItem('id')
            ->validate('Int', array(
                'min' => 1
            ))
            ->requireItem('title')
            ->validate('String', array(
                'minLength' => 5,
                'maxLength' => 500,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->editIdea(
                (int)$form->fetch('id'),
                Session::get('userId'),
                $form->fetch('title')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous Jquery Grid request for deleting an idea.
     */
    public function deleteIdea()
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
            $this->_library->deleteIdea(
                $form->fetch('id'),
                Session::get('userId')
            );
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }
}