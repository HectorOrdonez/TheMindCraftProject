<?php
/**
 *
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Database Library to manage the relation with the database engine.
 * This custom Database library extends PDO.
 * For more information about PDO, this might be of interest: http://blog.tordek.com.ar/2010/11/pdo-o-por-que-todos-los-tutoriales-de-php-llevan-a-las-malas-practicas/
 * Date: 12/06/13 11:30
 * @todo Research about security related to the misuse of the Database methods. Example, giving whole sql entry instead a string parameter when a table name expected.
 */

namespace engine;

class Database extends \PDO
{
    /**
     * Last Query run by this Class
     * @var String $_lastQuery
     */
    protected $_lastQuery = '';

    /**
     * Indicates to this Class if the queries must be executed.
     * @var Bool $_debugMode
     */
    protected $_debugMode = FALSE;

    /**
     * Contains the Statement going to be executed
     * @var \PDOStatement
     */
    protected $_statement = NULL;


    /**
     * Database constructor.
     * dbType, dbHost and dbName will turn into the data source name parameter required by the PDO class.
     * @param string $dbType MySql
     * @param string $dbHost Localhost
     * @param string $dbName Database name
     * @param string $dbUser User
     * @param string $dbPass Password
     * @throws DatabaseException
     */
    public function __construct($dbType, $dbHost, $dbName, $dbUser, $dbPass)
    {
        parent::__construct(
            "{$dbType}:host={$dbHost};dbname={$dbName}",
            $dbUser,
            $dbPass);
    }

    /**
     * Receives the unbind SQL instruction and stores it in the property _lastQuery.
     * Then the parent method prepare is called with it.
     * @param string $sql
     */
    protected function _prepare ($sql)
    {
        $this->_lastQuery = $sql;
        $this->_statement = parent::prepare($sql);
    }

    /**
     * Receives a field to bind to a given value.
     * Last Query gets replaced with it and then the value type is filtered in order to use the PDO bindValue with the correct parameter.
     *
     * @param string $field
     * @param mixed $value
     */
    protected function _bindValue ($field, $value)
    {
        $valueType = gettype($value);

        switch ($valueType)
        {
            case 'string':
                $this->_lastQuery = str_replace($field, "'{$value}'", $this->_lastQuery);
                $this->_statement->bindValue($field, $value, \PDO::PARAM_STR);
                break;
            case 'boolean':
                $this->_lastQuery = str_replace($field, "{$value}", $this->_lastQuery);
                $this->_statement->bindValue($field, $value, \PDO::PARAM_BOOL);
                break;
            default:
                $this->_lastQuery = str_replace($field, "{$value}", $this->_lastQuery);
                $this->_statement->bindValue($field, $value, \PDO::PARAM_INT);
                break;
        }
    }

    /**
     * Execute instruction. In case the debug mode is not enabled this means to execute the PDO Statement.
     */
    protected function _execute()
    {
        if ($this->_debugMode === FALSE)
        {
            try {
                $executionResult = $this->_statement->execute();
            } catch (\PDOException $e){
                // Temporal Code to learn about PDO Exceptions
                echo "PDOEXCEPTION : " . $e->getMessage();
                exit;
            }

            if ($executionResult === FALSE) {
                $errorInfo = $this->_statement->errorInfo();
                throw new DatabaseException ($errorInfo[2], DatabaseException::FATAL_EXCEPTION);
            }
        }
    }

    /**
     * Sets the Debug Mode.
     * @param Bool $debugMode
     */
    public function setDebugMode($debugMode)
    {
        $this->_debugMode = (bool) $debugMode;
    }

    /**
     * Returns the last Query.
     * @return String $_lastQuery
     */
    public function getLastQuery()
    {
        return $this->_lastQuery;
    }

    /**
     * Database Select instruction. To be used for simple selects.
     *
     * @param string $table Name of the database table from which extract the data.
     * @param array $fields Array of fields to select. Can be empty, in which case all fields of the table will be shown.
     * @param array $conditions Array of conditions to use in the select to filter the data, in the format of key(name of field)=>value(value in field)
     * @param int $fetchMode PDO Mode of presenting the data.
     * @return mixed
     */
    public function select($table, $fields = array(), $conditions = array(), $fetchMode = \PDO::FETCH_ASSOC)
    {
        // 1 - Preparing unbound query
        $selectQuery = 'SELECT ';
        $selectQuery .= (count($fields) == 0)? '* ' : implode(',', $fields);
        $selectQuery .= ' FROM ' . $table;

        if (count($conditions) > 0)
        {
            $conditionsString = ' WHERE ';
            foreach (array_keys($conditions) as $conditionedField) {
                $conditionsString .= "`$conditionedField` = :$conditionedField AND ";
            }
            $selectQuery .= substr($conditionsString, 0, -4);
        }
        $this->_prepare($selectQuery);

        // 2 - Binding Query
        foreach ($conditions as $conditionedField => $conditionalValue) {
            $this->_bindValue(":{$conditionedField}", $conditionalValue);
        }

        // 3 - Executing
        $this->_execute();

        // 4 - Returning results (step only in Selects)
        return $this->_statement->fetchAll($fetchMode);
    }

    /**
     * Database Insert instruction. Inserts data into given Database table.
     *
     * @param string $table Name of the Table to insert into.
     * @param array $data Associative array of data.
     */
    public function insert($table, $data)
    {
        // 1 - Preparing unbound query
        $fieldNames = implode('`,`', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        $this->_prepare('INSERT INTO ' . $table . ' (`' . $fieldNames . '`) VALUES (' . $fieldValues . ')');

        // 2 - Binding query
        foreach ($data as $key => $value) {
            $this->_bindValue(":{$key}", $value);
        }

        // 3 - Executing
        $this->_execute();
    }

    /**
     * Database Update instruction. Updates data from given Database table with set data for the specified conditions.
     *
     * @param string $table Name of the table to update.
     * @param array $set Data array of data to update in the form of renewedField=>newValue
     * @param array $conditions Conditions array in the form of conditionedField=>conditionalValue
     */
    public function update($table, $set, $conditions)
    {
        // 1 - Preparing unbound query
        $setString = '';
        $conditionsString = '';

        foreach (array_keys($set) as $renewedField) {
            $setString .= "`$renewedField` = :$renewedField,";
        }
        $setString = substr($setString, 0, -1);

        foreach (array_keys($conditions) as $conditionedField) {
            $conditionsString .= "`$conditionedField` = :$conditionedField AND";
        }
        $conditionsString = substr($conditionsString, 0, -4);

        $this->_prepare('UPDATE ' . $table . ' SET ' . $setString . ' WHERE '.$conditionsString);

        // 2 - Binding query
        foreach ($set as $renewedField => $newValue) {
            $this->_bindValue(":{$renewedField}", $newValue);
        }
        foreach ($conditions as $conditionedField => $conditionalValue) {
            $this->_bindValue(":{$conditionedField}", $conditionalValue);
        }

        // 3 - Executing
        $this->_execute();
    }

    /**
     * Database Delete instruction. Deletes data from given Database table for the specified conditions.
     *
     * @param string $table Name of the Table into which data will be deleted
     * @param array $conditions Conditions array in the form of conditionedField=>conditionalValue
     */
    public function delete($table, $conditions)
    {
        // 1 - Preparing unbound query
        $conditionsString = '';
        foreach (array_keys($conditions) as $conditionedField) {
            $conditionsString .= "`$conditionedField` = :$conditionedField AND";
        }
        $conditionsString = substr($conditionsString, 0, -4);

        $this->_prepare('DELETE FROM ' . $table . ' WHERE ' . $conditionsString);

        // 2 - Binding query
        foreach ($conditions as $conditionedField => $conditionalValue) {
            $this->_bindValue(":{$conditionedField}", $conditionalValue);
        }

        // 3 - Executing
        $this->_execute();
    }

    /**
     * Database complex sql. It can be any of the CRUD.
     *
     * @param string $sql
     * @param array $parameters
     * @param int $fetchMode
     * @return mixed
     */
    public function complexQuery ($sql, $parameters = array(), $fetchMode = \PDO::FETCH_ASSOC)
    {
        // 1 - Preparing unbound query
        $this->_prepare($sql);
        // 2 - Binding query
        foreach ($parameters as $parameterField => $parameterValue) {
            $this->_bindValue(":{$parameterField}", $parameterValue);
        }
        // 3 - Executing
        $this->_execute();
        // 4 - Returning results (step only in Selects)
        if (strtoupper(substr($sql, 0, 6)) == 'SELECT')
        {
            return $this->_statement->fetchAll($fetchMode);
        }
    }
}

namespace engine;

class DatabaseException extends Exception
{
    public function __construct($message = "", $exceptionType = self::GENERAL_EXCEPTION, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $exceptionType, $code, $previous);
    }
}