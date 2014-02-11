<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table routine.
 * Date: 07/01/14 02:30
 */

namespace application\models;

use ActiveRecord\ActiveRecordException;
use \ActiveRecord\Model as Model;

/**
 * Class Routine
 * @package application\models
 *
 * Magic methods
 * @method static Routine find_by_id(\int $id) Returns the Routine with given idea_id
 *
 * Magically accessed attributes
 * @property int idea_id Id
 * @property Idea idea Related idea
 * @property string $frequency_days Which days this Routine is to be applied
 * @property string $frequency_weeks Every how many weeks this Routine is active
 * @property \DateTime $date_start When this Routine starts
 * @property \DateTime $date_finish When this Routine ends
 * @property string $time_from Time in 24-hour format
 * @property string $time_till Time in 24-hour format
 */
class Routine extends Model
{
    public static $table_name = 'routine'; // Table name

    static $belongs_to = array(
        array('idea',
            'class_name' => 'Idea'
        )
    );
    
    static $primary_key = 'idea_id';



    /**
     * Returns an array with this routine parameters.
     *
     * @param array $requiredFields
     * @return array
     * @throws ActiveRecordException
     */
    public function toArray($requiredFields = array())
    {
        $rawRoutineArray = array(
            'id' => $this->idea_id,
            'user_id' => $this->idea->user_id,
            'title' => $this->idea->title,
            'date_creation' => $this->idea->date_creation->format('d/m/Y'),
            'frequency_days' => $this->frequency_days,
            'frequency_weeks' => $this->frequency_weeks,
            'date_start' => (is_null($this->date_start)) ? '' : $this->date_start->format('d/m/Y'),
            'date_finish' => (is_null($this->date_finish)) ? '' : $this->date_finish->format('d/m/Y'),
            'time_from' => (is_null($this->time_from)) ? '' : substr($this->time_from, 0, 5),
            'time_till' => (is_null($this->time_till)) ? '' : substr($this->time_till, 0, 5),
            'selected' => $this->idea->selected,
            'important' => $this->idea->important,
            'urgent' => $this->idea->urgent,
        );

        // If no required fields specified all fields are returned.
        if (empty($requiredFields)) {
            return $rawRoutineArray;
        }

        // Otherwise only specified fields are returned.
        $routineArray = array();
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $rawRoutineArray)) {
                throw new ActiveRecordException("Requested field {$field} does not belong to mission Model.");
            }
            $routineArray[$field] = $rawRoutineArray[$field];
        }
        return $routineArray;
    }
}