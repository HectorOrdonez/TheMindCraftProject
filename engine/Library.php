<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * The Library class of the Engine is the master of the Libraries, extended by the Library of the application engine and, that one, extended by some of the libraries that the Application needs.
 *
 * Libraries are design for multiple purposes. Here lays the Business logic of the application.
 *
 * All Controllers have one library assigned; this is because the Controllers does not run logic beyond the one required for validating sent data and manipulating the final data that the library will build.
 *
 * However, some libraries have assigned no Controllers. This is because a Library can have a general duty, required by more than one library.
 *
 * Libraries eventually make us of Models.
 *
 * Libraries return data, in case no errors are found.
 *
 * Libraries through exceptions in case the logic fails to accomplished expected results.
 * Date: 11/07/13 15:00
 */

namespace engine;

use application\engine\Model;

class Library
{
    /**
     * Model property that contains the Model that the library might require.
     * Not all Libraries has a model attached, and some might need more than one. This property only eases the job of the Libraries that needs a single Model.
     * @var Model $_model
     */
    protected $_model;

    /**
     * Library constructor.
     */
    public function __construct(Model $model = NULL)
    {
        $this->_model = $model;
    }
}