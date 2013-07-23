<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Date: 11/06/13 11:53
 */

namespace application\controllers;

use application\engine\Controller;
use engine\Exception;

class Error extends Controller
{
    /**
     * Error constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->_view->setMeta('description', array(
            'name' => 'description',
            'content' => 'Error in Selfology.'
        ));

        $this->_view->setMeta('author', array(
            'name' => 'author',
            'content' => 'Hector Ordonez'
        ));

        $this->_view->setMeta('keywords', array(
            'name' => 'keywords',
            'content' => 'Error'
        ));

        $this->_view->setTitle('Error');

        $this->_view->addLibrary('css', 'application/views/error/css/error.css');
    }

    /** General Error Page */
    public function index($error = 'No error message specified.')
    {
        $this->_view->setParameter('msg', $error);

        $this->_view->addChunk('error/index');
    }

    /**
     * Exception Page
     * Called when Bootstrap catches an Exception.
     * @param Exception $exception
     */
    public function exception (Exception $exception)
    {
        $this->_view->setParameter('exception', $exception->getMessage());
        $this->_view->setParameter('file', $exception->getRelativeFile());
        $this->_view->setParameter('line', $exception->getLine());
        $this->_view->setParameter('backtrace', $exception->getCustomTrace());

        $this->_view->addChunk('error/exception');
    }

    /**
     * Error #401 - Authentication Failed.
     */
    public function authFailed()
    {
        $this->_view->setParameter('msg', 'Authentication Failed');

        $this->_view->addChunk('error/index');
    }

    /**
     * Error #403 - Access Forbidden to this page.
     */
    public function accessForbidden()
    {
        $this->_view->setParameter('msg', 'Access Forbidden to this resource.');

        $this->_view->addChunk('error/index');
    }

    /**
     * Error #500 - Internal Server Error.
     */
    public function internalServerError()
    {
        $this->_view->setParameter('msg', 'Internal Server Error');

        $this->_view->addChunk('error/index');
    }

    /**
     * Error #404 - Resource not found.
     */
    public function resourceNotFound()
    {
        $this->_view->setParameter('msg', 'Requested resource not found.');

        $this->_view->addChunk('error/index');
    }
}
