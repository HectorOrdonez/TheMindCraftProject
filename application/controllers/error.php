<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Error Controller. Here the errors are displayed.
 * Date: 23/07/13 12:00
 */

namespace application\controllers;

use application\engine\Controller;
use engine\drivers\Exception;

class error extends Controller
{
    /**
     * Error constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->_view->setMeta('description', array(
            'name' => 'description',
            'content' => 'Error in The Mindcraft Project.'
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

        $this->_view->addLibrary('application/views/error/css/error.css');
    }

    /** General Error Page */
    public function index($error = 'There is an error, but no message D:!! What should I do, what should I do!? WHAT SHOULD I DO DAMN!!.')
    {
        $this->_view->setParameter('msg', $error);

        $this->_view->addChunk('error/index');
    }

    /**
     * Exception Page
     * Called when Bootstrap catches an Exception.
     * @param Exception $exception
     */
    public function exception(Exception $exception)
    {
        header('HTTP/1.1 500 Internal Server Error.');

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
        header('HTTP/1.1 401 Authentication failed.');
        $this->_view->setParameter('msg', 'Sorry dear, you are NOT authorized to see this. I fear I will have to send two missils to your location.');

        $this->_view->addChunk('error/index');
    }

    /**
     * Error #403 - Access Forbidden to this page.
     */
    public function accessForbidden()
    {
        header('HTTP/1.1 403 Access forbidden to this page.');

        $this->_view->setParameter('msg', 'MAAAAAAAAAAAAAN What the fuck are you doing here?!?.');

        $this->_view->addChunk('error/index');
    }

    /**
     * Error #500 - Internal Server Error.
     */
    public function internalServerError()
    {
        header('HTTP/1.1 500 Internal Server Error.');

        $this->_view->setParameter('msg', 'OH MY GOD, OH MY GOD, OH MY GOD, OH MY GOD, OH MY GOD, OH MY GOD, OH MY GOD, OH MY GOD, OH MY GOD SOMETHING IS WRONG.');

        $this->_view->addChunk('error/index');
    }

    /**
     * Error #404 - Resource not found.
     */
    public function resourceNotFound()
    {
        header('HTTP/1.1 404 Resource not found.');

        $this->_view->setParameter('msg', 'The controller exist man, but the method does not seem like. Better luck next time :D');

        $this->_view->addChunk('error/index');
    }
}
