<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Controller of the page ideaToAction.
 * Here the User can take the ideas that he or she brainstormed and manage them with the action possibilities provided through JQuery Grid.
 * Date: 23/07/13 16:00
 */

namespace application\controllers;

use application\engine\Controller;
use application\libraries\IdeaToActionLibrary;
use engine\Session;

class ideaToAction extends Controller
{
    /**
     * Defining $_library Library type.
     * @var IdeaToActionLibrary $_library
     */
    protected $_library;

    public function __construct()
    {
        parent::__construct(new IdeaToActionLibrary);

        $logged = Session::get('isUserLoggedIn');
        if ($logged == FALSE) {
            Session::destroy();
            header('location: ' . _SYSTEM_BASE_URL . 'error/accessForbidden');
        }
    }

    /**
     * Idea To Action index page.
     */
    public function index()
    {
        $this->_view->addLibrary('js', 'public/js/external/grid.locale-en.js');
        $this->_view->addLibrary('js', 'public/js/external/jquery.jqGrid.src.js');
        $this->_view->addLibrary('js', 'public/js/external/jquery-ui-1.10.3.custom.js');
        $this->_view->addLibrary('js', 'public/js/jqgridToolkit.js');
        $this->_view->addLibrary('js', 'application/views/ideaToAction/js/ideaToAction.js');

        $this->_view->addLibrary('css', 'public/css/jquery-ui-1.10.3.custom.css');
        $this->_view->addLibrary('css', 'public/css/ui.jqgrid.css');
        $this->_view->addLibrary('css', 'application/views/ideaToAction/css/ideaToAction.css');

        $this->_view->addChunk('ideaToAction/index');
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
                    'title'
                )
            ))
            ->requireItem('sord') // Get the direction of the sorting
            ->validate('Enum', array(
                'availableOptions' => array(
                    'asc',
                    'desc'
                )
            ));

        $response = $this->_library->getBrainstormedIdeas(
            Session::get('userId'),
            $form->fetch('page'),
            (int)$form->fetch('rows'),
            $form->fetch('sidx'),
            $form->fetch('sord')
        );

        header("Content-type: application/json;charset=utf-8");
        echo json_encode($response);
    }
}