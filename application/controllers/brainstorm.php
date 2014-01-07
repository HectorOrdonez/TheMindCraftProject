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
use application\services\BrainstormService;
use engine\Input;
use engine\Form;
use engine\Session;
use engine\drivers\Exception;
use engine\drivers\Exceptions\InputException;
use engine\drivers\Exceptions\RuleException;

class brainstorm extends Controller
{
    /**
     * Defining $_service Service type.
     * @var BrainstormService $_service
     */
    protected $_service;

    /**
     * Controller constructor for the Brainstorm page.
     */
    public function __construct()
    {
        parent::__construct(new BrainstormService());

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
            exit;
        }
    }

    /**
     * Brainstorm index page.
     * Loads the required JS libraries of this page, together with language and CSS styles for the grid and the page.
     */
    public function index()
    {
        $this->_view->addLibrary('public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('application/views/brainstorm/js/brainstorm.js');
        $this->_view->addLibrary('application/views/brainstorm/css/brainstorm.css');

        $this->_view->addChunk('brainstorm/index');
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     */
    public function getIdeas()
    {
        $response = $this->_service->getIdeas(Session::get('userId'));

        echo json_encode($response);
    }

    /**
     * Asynchronous Jquery Grid request for adding a new idea.
     */
    public function createIdea()
    {
        try {
            $inputIdeaName = Input::build('Text', 'title')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 200);

            $inputIdeaName->validate();

            $response = $this->_service->createIdea(Session::get('userId'), $inputIdeaName->getValue());

            print json_encode($response);

        } catch (InputException $iEx) {
            $errorMessage = 'Input error: ' . $iEx->getMessage();
            header("HTTP/1.1 400 {$errorMessage}");
            exit($errorMessage);
        } catch (RuleException $rEx) {
            $errorMessage = 'Invalid data: ' . $rEx->getMessage();
            header("HTTP/1.1 400 {$errorMessage}");
            exit($errorMessage);
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
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputIdeaName = Input::build('Text', 'title')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 200);

            $inputIdeaId->validate();
            $inputIdeaName->validate();

            $this->_service->editIdea(Session::get('userId'), $inputIdeaId->getValue(), $inputIdeaName->getValue());

        } catch (InputException $iEx) {
            $errorMessage = 'Input error: ' . $iEx->getMessage();
            header("HTTP/1.1 400 {$errorMessage}");
            exit($errorMessage);
        } catch (RuleException $rEx) {
            $errorMessage = 'Invalid data: ' . $rEx->getMessage();
            header("HTTP/1.1 400 {$errorMessage}");
            exit($errorMessage);
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
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');

            $inputIdeaId->validate();

            $this->_service->deleteIdea(Session::get('userId'), $inputIdeaId->getValue());

        } catch (InputException $iEx) {
            $errorMessage = 'Input error: ' . $iEx->getMessage();
            header("HTTP/1.1 400 {$errorMessage}");
            exit($errorMessage);
        } catch (RuleException $rEx) {
            $errorMessage = 'Invalid data: ' . $rEx->getMessage();
            header("HTTP/1.1 400 {$errorMessage}");
            exit($errorMessage);
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }
}