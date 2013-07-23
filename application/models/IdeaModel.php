<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: 
 * Date: 23/07/13 13:01
 */

namespace application\models;

use application\engine\Model;
use engine\Exception;

class IdeaModel extends Model
{
    /**
     * Fields of the Table staff
     * @var array
     */
    protected $ideaFields = array(
        'id',
        'user_id',
        'title',
        'date_creation'
    );

    /**
     * Idea Model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Special method of selection for grids. Requires an array of parameters with the followings:
     * user_id - User from which we want to extract the ideas.
     * sidx - Field to index the results.
     * sord - Sorting direction.
     * start - Starting page.
     * rows - Number of max rows of the list.
     *
     * @param array $parameters
     * @return array of ideas of the user.
     */
    public function getUserIdeasList($parameters)
    {
        $sql = 'SELECT ' . implode(',', $this->ideaFields) . ' FROM idea';
        $sql .= ' WHERE `user_id` = :user_id';
        $sql .= ' AND `date_todo` is NULL OR `date_todo` <= :today';
        $sql .= ' ORDER BY :sidx :sord';
        $sql .= ' LIMIT :start, :rows';

        $parameters['today'] = date('Y-m-d');

        $result = $this->db->complexQuery($sql, $parameters);

        return $result;
    }

    /**
     * Selects all ideas from User.
     *
     * @param int $userId User Id
     * @return array of ideas of the user.
     */
    public function getAllUserIdeas($userId)
    {
        $conditions = array(
            'user_id' => $userId
        );

        return $this->db->select('idea', $this->ideaFields, $conditions);
    }

    /**
     * Collects the data from a specific idea.
     *
     * @param int $ideaId idea.
     * @param int $userId owner of the idea.
     * @return array Idea data
     */
    public function selectById($ideaId, $userId)
    {
        $fields = $this->ideaFields;

        $conditions = array(
            'id' => $ideaId,
            'user_id' => $userId
        );

        $result = $this->db->select('idea', $fields, $conditions);

        if (count($result) > 0) {
            return $result[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Creates an idea with the specified parameters for given user.
     *
     * @param int $userId owner of the idea
     * @param string $title
     * @param string $date_creation
     */
    public function insert($userId, $title, $date_creation)
    {
        $valuesArray = array(
            'user_id' => $userId,
            'title' => $title,
            'date_creation' => $date_creation
        );

        $this->db->insert('idea', $valuesArray);
    }

    /**
     * Updates idea with the data sent in the array newData.
     * The newData contains the fields and values to update. Notice that:
     * 1 - Column Id cannot be modified.
     *
     * @param $ideaId int - Id of the Idea to update.
     * @param $userId int - Id of the User who should own the idea.
     * @param $newData array - Fields to update and their new values.
     * @throws Exception
     */
    public function update($ideaId, $userId, $newData)
    {
        $setArray = array();

        foreach ($newData as $setField => $setValue) {
            if (!in_array($setField, $this->ideaFields)) {
                throw new Exception('Error in the update of the table idea. The field ' . $setField . ' does not belong to this model.');
            }

            if ($setField == 'id') {
                throw new Exception('Idea table column id cannot be modified.');
            } else {
                $setArray[$setField] = $setValue;
            }
        }

        $conditionsArray = array(
            'id' => $ideaId,
            'user_id' => $userId
        );

        $this->db->update('idea', $setArray, $conditionsArray);
    }

    /**
     * Deletes idea.
     *
     * @param $ideaId int - Id of the idea to delete.
     * @param $userId int - Id of the user who should own the idea.
     */
    public function delete($ideaId, $userId)
    {
        $conditionsArray = array(
            'id' => $ideaId,
            'user_id' => $userId
        );

        $this->db->delete('idea', $conditionsArray);
    }
}