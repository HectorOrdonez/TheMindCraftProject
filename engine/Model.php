<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * The Model class of the Engine is the master of the Models, extended by the Model of the application engine and, that one, extended by all the models that the Application needs.
 *
 * A Model is the logical representation of a Database entity; it easies the Libraries task of requesting data to the Database.
 * Date: 11/06/13 12:30
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