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
        if (false === $logged) {
            Session::destroy();
            exit('Access forbidden');
        }
    }

    /**
     * Asynchronous Jquery Grid request for filling up the grid.
     */
    public function getIdeas()
    {
        $response = $this->_service->getIdeas(Session::get('userId'));

        echo json_encode($response);
    }
}