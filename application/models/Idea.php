<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table idea.
 * Date: 07/01/14 02:30
 */

namespace application\models;

use ActiveRecord\ActiveRecordException;
use \ActiveRecord\Model as Model;

/**
 * Class Idea
 * @package application\models
 *
 * Magic methods ...
 * @method static Idea find_by_id(\int $id) Returns the Idea with given id.
 *
 * Magically accessed attributes ...
 * @property int id
 * @property int $user_id
 * @property string $title
 * @property \DateTime $date_creation
 * @property \DateTime $date_todo
 * @property \DateTime $date_from
 * @property \DateTime $date_till
 * @property string $time_from Time in 24-hour format.
 * @property string $time_till Time in 24-hour format.
 * @property int $selected Either this idea is selected or not. 0 as false, 1 as true.
 * @property int $important Either this idea is important or not. 0 as false, 1 as true.
 * @property int $urgent Either this idea is urgent or not. 0 as false, 1 as true.
 */
class Idea extends Model
{
    public static $table_name = 'idea'; // Table name

    static $belongs_to = array(
        array('user')
    );

    /**
     * Returns an array with this idea parameters.
     *
     * @param array $requiredFields
     * @return array
     * @throws ActiveRecordException
     */
    public function toArray($requiredFields = array())
    {
        $rawIdeaArray = array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'date_creation' => $this->date_creation->format('d/m/Y'),
            'date_todo' => (is_null($this->date_todo)) ? '' : $this->date_todo->format('d/m/Y'),
            'time_from' => (is_null($this->time_from)) ? '' : substr($this->time_from, 0, 5),
            'time_till' => (is_null($this->time_till)) ? '' : substr($this->time_till, 0, 5),
            'selected' => $this->selected,
            'important' => $this->important,
            'urgent' => $this->urgent,
        );

        // If no required fields specified, all fields are returned.
        if (empty($requiredFields)) {
            return $rawIdeaArray;
        }

        // Otherwise, only specified fields are returned.
        $ideaArray = array();
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $this->attributes())) {
                throw new ActiveRecordException("Requested field {$field} does not belong to idea Model.");
            }
            $ideaArray[$field] = $rawIdeaArray[$field];
        }
        return $ideaArray;
    }
}