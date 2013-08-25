<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Brainstorm.
 * This page provides the user with a tool to brainstorm tasks and ideas, things he or she wants to do.
 * These will be listed and displayed, giving the User the possibility to edit or delete them.
 *
 * The brainstormed ideas will be required when User wants to go from ideas to actions.
 * Date: 23/07/13 13:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\BrainstormLibrary;
use engine\Exception;
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
     * Loads the required JS libraries of this page, together with language and CSS styles for the grid and the page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('css', 'public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('js', 'application/views/brainstorm/js/brainstorm.js');
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

        $response = $this->_library->getIdeas(
            Session::get('userId')
        );

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
                'maxLength' => 200,
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $response = $this->_library->createIdea(
                Session::get('userId'),
                $form->fetch('title')
            );

            echo json_encode($response);
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

        try {
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
                    'maxLength' => 200,
                ));

            if (count($form->getErrors()) > 0) {
                header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
                exit;
            }

            // Executing
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