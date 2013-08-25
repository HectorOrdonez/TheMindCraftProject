<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Database class of the application engine.
 * Date: 11/07/13 12:00
 */

namespace application\engine;

use engine\Database as engineDatabase;

class Database extends engineDatabase
{
    /**
     * Database constructor of the application engine.
     *
     * @param string $dbType MySql
     * @param string $dbHost Localhost
     * @param string $dbName Database name
     * @param string $dbUser User
     * @param string $dbPass Password
     */
    public function __construct($dbType, $dbHost, $dbName, $dbUser, $dbPass)
    {
        parent::__construct($dbType, $dbHost, $dbName, $dbUser, $dbPass);
    }
}