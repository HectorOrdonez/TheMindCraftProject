<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * The Controller class of the Engine is the master of the Controllers, extended by the Controller of the application engine and, that one, extended by all the controllers that the Application needs.
 *
 * The Controllers are design to manage Users requests, validating their data and to decide which Services use to build the data that needs to be shown in the Views that the User request has assigned.
 *
 * Although the Controller is "blinded" of the logic required for building the data, the final data to be shown in the View travels through this class. Because of this, the Controller
 * must understand what the data looks like - that means, the Controller does not pass the final data to the view blindly; the Controller gets the final data and extracts from it the pieces that will be passed to the View.
 *
 * The requests that the Controllers receive might be synchronous or asynchronous - this is, ajax or not ajax calls -. Synchronous requests generate full web pages, but asynchronous build partial views, json data, xml, pdf, etc.
 * Because of this the Controllers have access to the methods setAutoRender and render (for more info read the documentation in these methods comments), which allows the logic not to follow the default behavior of the system, which is rendering a web page after the Controller ends processing the request.
 * So when an asynchronous call hits a Controller, this will have to disable the auto rendering, in order to only show the information that the asynchronous request requires.
 *
 * Controllers have the duty to manage the Exceptions that the Services throw; Controllers must know what to do when an error arises, even if this means calling another service to manage the error final data.
 * @date: 11/06/13 12:00
 */

namespace engine;

use application\engine\Service;
use application\engine\View;

use engine\drivers\Exceptions\ViewException;

/**
 * Class Controller
 * @package engine
 */
class Controller
{
    /**
     * Property view
     * @var null|View
     */
    protected $_view = NULL;

    /**
     * @var null
     */
    protected $_service = NULL;

    /*************************/
    /* Controller Settings  **/
    /*************************/

    /**
     * Controller constructor.
     *
     * Initializes the User Session.
     * Initializes the View and the Model.
     *
     * @param Service $service 
     */
    public function __construct(Service $service = NULL)
    {
        $this->_setView();
        $this->_setService($service);
    }

    protected function _setView()
    {
        $this->_view = new View;
    }

    /**
     * Auto-loading of the service related to this controller.
     * Checks if there is a service related to this controller and, if so, instantiates it.
     *
     * @param Service $service in which this controller can search for the service
     */
    protected function _setService(Service $service = NULL)
    {
        if (!is_null($service)) {
            $this->_service = $service;
        }
    }

    /**
     * Requested by the Bootstrap, the Render method cannot be extended by Controller children.
     * Verifies if the autoRender is enabled and, if so, requested the rendering of the view.
     * @todo Add Logging for Exception control, as these exceptions are critic.
     */
    final public function render()
    {
        try {
            $this->_view->printChunk();
        }
        catch (ViewException $vEx)
        {
            echo 'Fatal error in View: ' . $vEx->getMessage();
        }
    }
}