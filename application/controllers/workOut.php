<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Controller of the page Work Out.
 * Here the User can take the ideas that he or she brainstormed and manage them with the action possibilities provided through JQuery Grid.
 * Date: 23/07/13 16:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\WorkoutLibrary;
use engine\Exception;
use engine\Form;
use engine\Session;

class workOut extends Controller
{
    /**
     * Defining $_library Library type.
     * @var WorkoutLibrary $_library
     */
    protected $_library;

    public function __construct()
    {
        parent::__construct(new WorkoutLibrary);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Work out index page.
     */
    public function index($startingStep = 'stepSelection')
    {
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/grid.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/table.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/row.js');
        $this->_view->addLibrary('js', 'public/js/helpers/gridElements/cell.js');
        $this->_view->addLibrary('css', 'public/css/helpers/gridElements/gridElements.css');

        $this->_view->addLibrary('js', 'application/views/workOut/js/workOut.js');
        $this->_view->addLibrary('js', 'application/views/workOut/js/selection.js');
        $this->_view->addLibrary('js', 'application/views/workOut/js/timing.js');
        $this->_view->addLibrary('js', 'application/views/workOut/js/prioritizing.js');

        $this->_view->addLibrary('css', 'application/views/workOut/css/workOut.css');

        $this->_view->setParameter('startingStep', $startingStep);

        $this->_view->addChunk('workOut/index');
    }

    public function loadStepChunk()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $form = new Form;
        $form
            ->requireItem('step') //Get the page requested
            ->validate('Enum', array(
                'availableOptions' => array(
                    'stepSelection',
                    'stepTiming',
                    'stepPrioritizing'
                )
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        switch($form->fetch('step'))
        {
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
                header("HTTP/1.1 400 " . 'Fatal Error: unexpected step ' . $form->fetch('step') . '.');
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
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        $response = $this->_library->getIdeasForStep(
            Session::get('userId'),
            $step
        );

        echo json_encode($response);
    }

    /**
     * Asynchronous Jquery Grid request for holding over an idea.
     */
    public function holdOverIdea()
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
            ->requireItem('date_todo')
            ->validate('String', array(
                'minLength' => 10,
                'maxLength' => 10
            ));

        if (count($form->getErrors()) > 0) {
            header("HTTP/1.1 400 " . implode('<br />', $form->getErrors()));
            exit;
        }

        // Executing
        try {
            $this->_library->holdOverIdea(
                (int)$form->fetch('id'),
                Session::get('userId'),
                $form->fetch('date_todo')
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

    public function generateActionPlan()
    {
        // Disabling auto render as this is an asynchronous request.
        $this->setAutoRender(FALSE);

        try {
        // Turning ideas into actions
        $this->_library->generateActionPlan(Session::get('userId'));
        } catch (Exception $e)
        {
            header("HTTP/1.1 500 " . $e->getMessage());
            exit($e->getMessage());
        }
    }
}