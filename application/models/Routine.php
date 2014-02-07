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
 * Class Routine
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
 * @property int $selected Either this idea is selected or not. 0 as false, 1 as true.
 * @property int $important Either this idea is important or not. 0 as false, 1 as true.
 * @property int $urgent Either this idea is urgent or not. 0 as false, 1 as true.
 * @property string $frequency_days
 * @property string $frequency_weeks
 * @property \DateTime $date_start
 * @property \DateTime $date_finish
 * @property string $time_from Time in 24-hour format.
 * @property string $time_till Time in 24-hour format.
 */
class Routine extends Model
{
    public static $table_name = 'routine'; // Table name

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
            'selected' => $this->selected,
            'important' => $this->important,
            'urgent' => $this->urgent,
            'frequency_days' => $this->frequency_days,
            'frequency_weeks' => $this->frequency_weeks,
            'date_start' => (is_null($this->date_start)) ? '' : $this->date_start->format('d/m/Y'),
            'date_finish' => (is_null($this->date_finish)) ? '' : $this->date_finish->format('d/m/Y'),
            'time_from' => (is_null($this->time_from)) ? '' : substr($this->time_from, 0, 5),
            'time_till' => (is_null($this->time_till)) ? '' : substr($this->time_till, 0, 5),
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