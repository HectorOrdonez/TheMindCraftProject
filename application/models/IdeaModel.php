<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table idea.
 * Date: 23/07/13 13:00
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
        'date_creation',
        'date_todo',
        'time_todo',
        'priority',
        'frequency',
        'postponed',
    );

    /**
     * Idea Model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Selects all postponed ideas from User.
     *
     * @param int $userId
     * @return array of ideas of the user.
     */
    public function getPostponedIdeas($userId)
    {
        $result = $this->db->select('idea', $this->ideaFields, array(
            'user_id' => $userId,
            'postponed' => true
        ));

        return $result;
    }

    /**
     * Selects all active ideas from User.
     * An idea is considered active if the date to do it is for today or before, and if it is not postponed.
     * Ideas which date to do is in the future are considered inactive for now; they will need to be done in the future.
     * Postponed ideas are ideas that the User prefers not to do them now but in the future it might be interesting to
     * do them.
     *
     * @param int $userId User Id
     * @return array of ideas of the user.
     */
    public function getUserActiveIdeas($userId)
    {
        $sql = 'SELECT ' . implode(',', $this->ideaFields) . ' FROM idea';
        $sql .= ' WHERE `user_id` = :user_id';
        $sql .= ' AND `postponed` = FALSE ';

        $parameters = array();
        $parameters['user_id'] = $userId;

        $result = $this->db->complexQuery($sql, $parameters);

        return $result;
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
     * @return string new idea Id
     */
    public function insert($userId, $title, $date_creation)
    {
        $valuesArray = array(
            'user_id' => $userId,
            'title' => $title,
            'date_creation' => $date_creation
        );

        $this->db->insert('idea', $valuesArray);

        return $this->db->lastInsertId();
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