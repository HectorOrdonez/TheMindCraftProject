<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * MindFlow page is the step manager. Offers the brainStorm, WorkOut and PerForm steps.
 * Date: 8/01/14 15:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\MindFlowService;
use engine\Input;
use engine\Session;
use engine\drivers\Exception;
use engine\drivers\Exceptions\InputException;
use engine\drivers\Exceptions\RuleException;

/**
 * Class mindFlow
 * @package application\controllers
 */
class mindFlow extends Controller
{
    /**
     * Defining $_service Service type.
     * @var MindFlowService $_service
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new MindFlowService());

        $logged = Session::get('isUserLoggedIn');
        if ($logged === FALSE) {
            header('location: ' . _SYSTEM_BASE_URL . 'index');
        }
    }

    /**
     * MindFlow index page.
     */
    public function index($step = 'step1')
    {
        // Table related libraries
        $this->_view->addLibrary('public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('public/css/helpers/gridElements/gridElements.css');

        // MindFlow libraries
        $this->_view->addLibrary('application/views/mindFlow/css/mindFlow.css');
        $this->_view->addLibrary('application/views/mindFlow/js/mindFlow.js');

        // Step related libraries
        $this->_view->addLibrary('application/views/mindFlow/js/brainStorm.js');
        $this->_view->addLibrary('application/views/mindFlow/js/selection.js');
        $this->_view->addLibrary('application/views/mindFlow/js/applyTime.js');
        $this->_view->addLibrary('application/views/mindFlow/js/prioritize.js');

        // Additional libraries
        $this->_view->addLibrary('public/js/external/jquery.transit.js');
        $this->_view->addLibrary('public/js/external/jquery-timepicker.js');

        $this->_view->setParameter('initStep', $step);
        $this->_view->addChunk('mindFlow/index');

    }

    /**
     * Asynchronous idea list request.
     * The required parameter tells the MindFlow for which stage the list is for, as every stage needs different info.
     */
    public function getIdeas()
    {
        try {
            $selectedStep = Input::build('Select', 'step')
                ->addRule('availableOptions', array('brainStorm', 'select', 'prioritize', 'applyTime'));
            $selectedStep->validate();
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error.');
            print($e->getMessage());
            exit;
        }
        
        $response = $this->_service->getIdeas(Session::get('userId'),$selectedStep->getValue());

        echo json_encode($response);
    }

    /**
     * New idea request.
     */
    public function newIdea()
    {
        try {
            $inputIdeaName = Input::build('Text', 'title')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 200);

            $inputIdeaName->validate();

            $response = $this->_service->newIdea(Session::get('userId'), $inputIdeaName->getValue());

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
     * Edit idea request.
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
     * Delete idea request.
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

    /**
     * Request to apply time to an idea.
     */
    public function applyTimeToIdea()
    {
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputIdeaDate = Input::build('Date', 'date');
            $inputIdeaTime = Input::build('Text', 'time')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 5);
            $inputIdeaFrequency = Input::build('Multiselect', 'howOften')
                ->addRule('availableOptions', array(
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday'
                ));

            $inputIdeaId->validate();
            $inputIdeaDate->validate();
            $inputIdeaTime->validate();
            $inputIdeaFrequency->validate();

            $this->_service->applyTimeToIdea(Session::get('userId'), $inputIdeaId->getValue(), $inputIdeaDate->getValue(), $inputIdeaTime->getValue(), $inputIdeaFrequency->getValue());

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
     * Prioritize idea request.
     */
    public function prioritizeIdea()
    {
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputIdeaPriority = Input::build('Number', 'priority')
                ->addRule('isInt')
                ->addRule('min', 1)
                ->addRule('max', 10);
                
            $inputIdeaId->validate();
            $inputIdeaPriority->validate();

            $this->_service->prioritizeIdea(Session::get('userId'), $inputIdeaId->getValue(), $inputIdeaPriority->getValue());

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
     * Set idea selection state request.
     */
    public function setIdeaSelection()
    {
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputIdeaSelectionState = Input::build('Select', 'selected')
                ->addRule('availableOptions', array('true', 'false'));
                
            $inputIdeaId->validate();
            $inputIdeaSelectionState->validate();

            $this->_service->setIdeaSelectionState(Session::get('userId'), $inputIdeaId->getValue(), $inputIdeaSelectionState->getValue());
            
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