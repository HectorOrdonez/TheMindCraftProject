<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * The Model class of the Engine is the master of the Models, extended by the Model of the application engine and, that one, extended by all the models that the Application needs.
 *
 * A Model is the logical representation of a Database entity; it easies the Libraries task of requesting data to the Database.
 * Date: 11/06/13 12:30
 * @todo Provide properties and methods to allow the management of the tables field related to this model. This way it can be easier to validate column names.
 * @todo Method required in all models: count() - Returns the amount of records the table related to this models contains.
 * @todo Possibility to create an Interface Model with a list of required methods all models must instantiate?
 */

namespace engine;

class Model
{
    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }
}