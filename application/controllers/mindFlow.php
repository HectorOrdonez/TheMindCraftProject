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
use application\models\Idea;
use application\models\Mission;
use application\models\Routine;
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

        $response = $this->_service->getIdeas(Session::get('userId'), $selectedStep->getValue());

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
        } catch (\Exception $e) {
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

    /**
     * Set idea important state request.
     */
    public function setIdeaImportant()
    {
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputIdeaImportantState = Input::build('Select', 'important')
                ->addRule('availableOptions', array('true', 'false'));

            $inputIdeaId->validate();
            $inputIdeaImportantState->validate();

            $this->_service->setIdeaImportantState(Session::get('userId'), $inputIdeaId->getValue(), $inputIdeaImportantState->getValue());

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
     * Set idea urgent state request.
     */
    public function setIdeaUrgent()
    {
        try {
            $inputIdeaId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputIdeaUrgentState = Input::build('Select', 'urgent')
                ->addRule('availableOptions', array('true', 'false'));

            $inputIdeaId->validate();
            $inputIdeaUrgentState->validate();

            $this->_service->setIdeaUrgentState(Session::get('userId'), $inputIdeaId->getValue(), $inputIdeaUrgentState->getValue());

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
     * Set idea urgent state request.
     */
    public function setIdeaRoutine()
    {
        $rand = rand(0, 1);

        if ($rand == 0) {
            print json_encode(array('success'));
        } else {
            header("HTTP/1.1 500 " . 'Unexpected error: ' . 'This is a random error.');
            exit;
        }
    }

    public function test()
    {
        echo '<pre>';

        echo '<br><br><br>Getting all Missions owned by User 11' . "<hr>";

        $missions = Mission::find('all', array(
            'user_id' => 11
        ));

        foreach ($missions as $mission) {
            echo var_export($mission->toArray(), true);
        }
    }

    public function setMissionDateTime()
    {
        try {
            $inputMissionId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputMissionDateTodo = Input::build('Date', 'date_todo');
            $inputMissionTimeFrom = Input::build('Text', 'time_from')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 5);
            $inputMissionTimeTill = Input::build('Text', 'time_till')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 5);

            $inputMissionId->validate();

            if ('' != $inputMissionDateTodo->getValue()) {
                $inputMissionDateTodo->validate();

                $date_todo = \DateTime::createFromFormat('d/m/Y', $inputMissionDateTodo->getValue());
            } else {
                $date_todo = null;
            }

            // Time frame is verified in case they are not sent empty, in which case time frame is not set.
            if ('' != $inputMissionTimeFrom->getValue() || '' != $inputMissionTimeTill->getValue()) {
                $inputMissionTimeFrom->validate();
                $inputMissionTimeTill->validate();

                $timeFrom = $inputMissionTimeFrom->getValue();
                $timeTill = $inputMissionTimeTill->getValue();
            } else {
                $timeFrom = null;
                $timeTill = null;
            }

            $this->_service->setMissionDateTime(Session::get('userId'), $inputMissionId->getValue(), $date_todo, $timeFrom, $timeTill);

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
    
    public function setRoutineDateTime()
    {
        try {
            $inputRoutineId = Input::build('Number', 'id')
                ->addRule('isInt');
            $inputRoutineDateStart = Input::build('Date', 'date_start');
            $inputRoutineDateFinish = Input::build('Date', 'date_finish');
            $inputRoutineTimeFrom = Input::build('Text', 'time_from')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 5);
            $inputRoutineTimeTill = Input::build('Text', 'time_till')
                ->addRule('minLength', 5)
                ->addRule('maxLength', 5);

            $inputRoutineId->validate();

            if ('' != $inputRoutineDateStart->getValue()) {
                $inputRoutineDateStart->validate();
                $dateStart = \DateTime::createFromFormat('d/m/Y', $inputRoutineDateStart->getValue());
            } else {
                $dateStart = null;
            }
            
            if ('' != $inputRoutineDateFinish->getValue()) {
                $inputRoutineDateFinish->validate();
                $dateFinish = \DateTime::createFromFormat('d/m/Y', $inputRoutineDateFinish->getValue());
            } else {
                $dateFinish = null;
            }

            // Time frame is verified in case they are not sent empty, in which case time frame is not set.
            if ('' != $inputRoutineTimeFrom->getValue() || '' != $inputRoutineTimeTill->getValue()) {
                $inputRoutineTimeFrom->validate();
                $inputRoutineTimeTill->validate();

                $timeFrom = $inputRoutineTimeFrom->getValue();
                $timeTill = $inputRoutineTimeTill->getValue();
            } else {
                $timeFrom = null;
                $timeTill = null;
            }

            $this->_service->setRoutineDateTime(Session::get('userId'), $inputRoutineId->getValue(), $dateStart, $dateFinish, $timeFrom, $timeTill);

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