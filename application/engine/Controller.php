<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Controller class of the application engine.
 * Date: 11/07/13 12:00
 */

namespace application\engine;

use engine\Controller as engineController;
use engine\Session;

class Controller extends engineController
{
    /**
     * Controller constructor of the application engine.
     *
     * @param Library $library in which this Controller can search for the Model
     */
    public function __construct(Library $library = NULL)
    {
        parent::__construct($library);
    }

    /**
     * Method setView.
     * Requests the setView method of the engine/Controller. Then it executes the setView logic that the application needs.
     */
    protected function _setView()
    {
        parent::_setView();

        $this->_view->setTitle('Selfology');
        $this->_view->addLibrary('css', 'public/css/default.css');

        $this->_view->addLibrary('js', 'public/js/external/jquery-1.10.1.js');
        $this->_view->addLibrary('js', 'public/js/general.js');

        $this->_view->setMeta('description', array(
            'name' => 'description',
            'content' => 'This is a sample website for Selfology'
        ));

        $this->_view->setMeta('author', array(
            'name' => 'author',
            'content' => 'Hector Ordonez'
        ));

        $this->_view->setMeta('http-equiv', array(
            'http-equiv' => 'Content-Type',
            'content' => 'text/html; charset=UTF-8'
        ));

        $this->_view->setMeta('keywords', array(
            'name' => 'keywords',
            'content' => 'Selfology, PHP, JavaScript, OOP, MVC'
        ));

        if (Session::get('isUserLoggedIn') == TRUE)
        {
            $this->_view->setHeaderChunk('application/views/general/logged_header.php');
            $this->_view->setFooterChunk('application/views/general/logged_footer.php');
        } else {
            $this->_view->setHeaderChunk('application/views/general/nonLogged_header.php');
            $this->_view->setFooterChunk('application/views/general/nonLogged_footer.php');
        }

        if (Session::get('userRole') == 'admin')
        {
            $this->_view->setFooterChunk('application/views/general/admin_footer.php');
            $this->_view->setParameter('myName', Session::get('userName'));
        }

        $this->_view->setParameter('userLogin', Session::get('isUserLoggedIn'));
        $this->_view->setParameter('userRole', Session::get('userRole'));
    }
}