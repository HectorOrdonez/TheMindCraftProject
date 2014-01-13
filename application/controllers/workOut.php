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
        if (false === $logged) {
            Session::destroy();
            exit('Access forbidden');
        }
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     * The required parameter tells the Work Out Controller for which stage the ideas are requested, as every stage needs different info.
     *
     * @param string $step (stepSelection, stepApplyTime, stepPrioritizing)
     */
    public function getIdeas($step)
    {
        $response = $this->_service->getIdeasForStep(
            Session::get('userId'),
            $step
        );

        echo json_encode($response);
    }
}