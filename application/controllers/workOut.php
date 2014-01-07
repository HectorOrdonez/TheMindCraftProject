<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page Work Out.
 * Here the User can take the ideas that he or she brainstormed and manage them with the action possibilities provided through JQuery Grid.
 * Date: 23/07/13 16:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\services\WorkOutService;
use engine\Input;
use engine\drivers\Exceptions\RuleException;
use engine\drivers\Exception;
use engine\Session;

class workOut extends Controller
{
    /**
     * Defining $_service Service type.
     * @var WorkOutService $_service
     */
    protected $_service;

    public function __construct()
    {
        parent::__construct(new WorkOutService);

        $logged = Session::get('isUserLoggedIn');
        if ($logged === FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Work out index page.
     */
    public function index($startingStep = 'stepSelection')
    {
        $this->_view->addLibrary('public/js/external/jquery-ui-1.10.3.custom.js');
        $this->_view->addLibrary('public/js/external/jquery-timepicker.js');
        $this->_view->addLibrary('public/css/external/jquery-ui-1.10.3.custom.css');

        $this->_view->addLibrary('public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('application/views/workOut/js/workOut.js');
        $this->_view->addLibrary('application/views/workOut/js/selection.js');
        $this->_view->addLibrary('application/views/workOut/js/timing.js');
        $this->_view->addLibrary('application/views/workOut/js/prioritizing.js');

        $this->_view->addLibrary('application/views/workOut/css/workOut.css');

        $this->_view->setParameter('startingStep', $startingStep);

        $this->_view->addChunk('workOut/index');
    }

    public function loadStepChunk()
    {
        try {
            $inputStep = Input::build('Select', 'step')->addRule('availableOptions', array(
                'stepSelection',
                'stepTiming',
                'stepPrioritizing'
            ));
            $inputStep->validate();
        } catch (RuleException $rEx) {
            header("HTTP/1.1 400 " . $rEx->getMessage());
            exit;
        }
        
        switch ($inputStep->getValue()) {
            case 'stepSelection':
                require _SYSTEM_ROOT_PATH . 'application/views/workOut/selection.php';
                break;
            case 'stepTiming':
                require _SYSTEM_ROOT_PATH . 'application/views/workOut/timing.php';
                break;
            case 'stepPrioritizing':
                require _SYSTEM_ROOT_PATH . 'application/views/workOut/prioritizing.php';
                break;
            default:
                header("HTTP/1.1 400 " . 'Fatal Error: unexpected step ' . $inputStep->getValue(). '.');
        }
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     * The required parameter tells the Work Out Controller for which stage the ideas are requested, as every stage needs different info.
     *
     * @param string $step (stepSelection, stepTiming, stepPrioritizing)
     */
    public function getIdeas($step)
    {
        $response = $this->_service->getIdeasForStep(
            Session::get('userId'),
            $step
        );

        echo json_encode($response);
    }

    /**
     * Asynchronous Jquery Grid request for adding a new idea.
     */
    public function createIdea()
    {
    }

    /**
     * Asynchronous Jquery Grid request for editing an idea.
     */
    public function editIdea()
    {
    }

    /**
     * Asynchronous Jquery Grid request for deleting an idea.
     */
    public function deleteIdea()
    {
    }

    /**
     * Asynchronous Jquery Grid request for holding over an idea.
     */
    public function holdOverIdea()
    {
        try {
            $inputIdeaId = Input::build('Number', 'id')->addRule('isInt');
            $inputIdeaId->validate();
            $this->_service->holdOverIdea(Session::get('userId'), $inputIdeaId->getValue());
        } catch (RuleException $rEx)
        {
            header("HTTP/1.1 400 " . $rEx->getMessage());
            exit;
        } catch (Exception $e) {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . $e->getMessage());
            exit;
        }
    }

    /**
     * Asynchronous Jquery Grid request for apply time to an idea.
     */
    public function applyTimeIdea()
    {
    }

    /**
     * Asynchronous Jquery Grid request for setting a priority to an idea.
     */
    public function setPriorityToIdea()
    {
    }

    /**
     * Collects the User Ideas and turns them into actions.
     */
    public function generateActionPlan()
    {
    }
}