<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table action.
 * Date: 13/02/14 22:30
 */

namespace application\models;

use ActiveRecord\ActiveRecordException;
use \ActiveRecord\Model as Model;

/**
 * Class Action
 * @package application\models
 *
 * Magic methods ...
 *
 * Magically accessed attributes ...
 * @property int id Id of this table
 * @property int $user_id Foreign key to users
 * @property int $routine_id Foreign key to routines. To mission-born actions this field is null
 * @property string $title Action name
 * @property \DateTime $date_creation When this action was created
 * @property \DateTime $date_todo When this action is planned to be done
 * @property string $time_from Time in 24-hour format
 * @property string $time_till Time in 24-hour format
 * @property \DateTime $date_done When this action has been done
 * @property int $important Either this idea is important or not. 0 as false, 1 as true.
 * @property int $urgent Either this idea is urgent or not. 0 as false, 1 as true.
 */
class Action extends Model
{
    public static $table_name = 'action'; // Table name

    static $belongs_to = array(
        array('user', 'class_name' => 'User'),
    );

    /**
     * Returns an array with this action parameters.
     *
     * @param array $requiredFields
     * @return array
     * @throws ActiveRecordException
     */
    public function toArray($requiredFields = array())
    {
        $rawActionArray = array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'routine_id' => $this->routine_id,
            'title' => $this->title,
            'date_creation' => $this->date_creation->format('d/m/Y'),
            'date_todo' => (is_null($this->date_todo)) ? '' : $this->date_todo->format('d/m/Y'),
            'time_from' => (is_null($this->time_from)) ? '' : substr($this->time_from, 0, 5),
            'time_till' => (is_null($this->time_till)) ? '' : substr($this->time_till, 0, 5),
            'date_done' => (is_null($this->date_done)) ? '' : $this->date_done->format('d/m/Y'),
            'important' => $this->important,
            'urgent' => $this->urgent,
        );

        // If no required fields specified all fields are returned.
        if (empty($requiredFields)) {
            return $rawActionArray;
        }

        // Otherwise only specified fields are returned.
        $actionArray = array();
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $this->attributes())) {
                throw new ActiveRecordException("Requested field {$field} does not belong to action Model.");
            }
            $actionArray[$field] = $rawActionArray[$field];
        }
        return $actionArray;
    }
}