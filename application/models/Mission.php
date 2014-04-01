<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table mission.
 * Date: 07/01/14 02:30
 */

namespace application\models;

use ActiveRecord\ActiveRecordException;
use \ActiveRecord\Model as Model;

/**
 * Class Mission
 * @package application\models
 *
 * Magic methods 
 * @method static Mission find_by_pk(\int $id) Returns the Mission with given idea_id
 *
 * Magically accessed attributes
 * @property int idea_id Id
 * @property Idea idea Related idea
 * @property \DateTime $date_todo When this mission has to be done
 * @property string $time_from Time in 24-hour format
 * @property string $time_till Time in 24-hour format
 */
class Mission extends Model
{
    public static $table_name = 'mission'; // Table name

    static $belongs_to = array(
        array('idea', 'class_name' => 'Idea'),
        array('user', 'through' => 'Idea', 'class_name' => 'User')
    );
    
    static $primary_key = 'idea_id';

    /**
     * Returns an array with this mission parameters.
     *
     * @param array $requiredFields
     * @return array
     * @throws ActiveRecordException
     */
    public function toArray($requiredFields = array())
    {
        $rawMissionArray = array(
            'id' => $this->idea_id,
            'user_id' => $this->idea->user_id,
            'title' => $this->idea->title,
            'date_creation' => $this->idea->date_creation->format('d/m/Y'),
            'date_todo' => (is_null($this->date_todo)) ? '' : $this->date_todo->format('d/m/Y'),
            'time_from' => (is_null($this->time_from)) ? '' : substr($this->time_from, 0, 5),
            'time_till' => (is_null($this->time_till)) ? '' : substr($this->time_till, 0, 5),
            'selected' => $this->idea->selected,
            'important' => $this->idea->important,
            'urgent' => $this->idea->urgent,
        );

        // If no required fields specified all fields are returned.
        if (empty($requiredFields)) {
            return $rawMissionArray;
        }

        // Otherwise only specified fields are returned.
        $missionArray = array();
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $rawMissionArray)) {
                throw new ActiveRecordException("Requested field {$field} does not belong to mission Model.");
            }
            $missionArray[$field] = $rawMissionArray[$field];
        }
        return $missionArray;
    }

    /**
     * Turns this mission into an action.
     * Creates the action with its data based on this mission.
     * Calls to this mission idea's delete method, which will delete this mission record too.
     */
    public function turnIntoAction()
    {
        Action::create(array(
            'user_id' => $this->idea->user_id,
            'title' => $this->idea->title,
            'date_creation' => date('Y-m-d'),
            'date_todo' => ('' == $this->date_todo)? null : $this->date_todo->format('Y-m-d'),
            'time_from' => $this->time_from,
            'time_till' => $this->time_till,
            'important' => $this->idea->important,
            'urgent' => $this->idea->urgent
        ));
        
        $this->idea->delete();
    }
}