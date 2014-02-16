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
 * @method static Idea[] find(array $options) Returns an array of Ideas that fit the passed conditions.
 *
 * Magically accessed attributes ...
 * @property int id
 * @property int user_id
 * @property string $title
 * @property string $type ['mission', 'routine']
 * @property \DateTime $date_creation
 * @property int $selected Either this idea is selected or not. 0 as false, 1 as true.
 * @property int $important Either this idea is important or not. 0 as false, 1 as true.
 * @property int $urgent Either this idea is urgent or not. 0 as false, 1 as true.
 */
class Idea extends Model
{
    const MISSION_TYPE = 'mission';
    const ROUTINE_TYPE = 'routine';
    
    public static $table_name = 'idea'; // Table name

    static $belongs_to = array(
        array('user', 'class_name' => 'User'),
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
        );

        // If no required fields specified all fields are returned.
        if (empty($requiredFields)) {
            return $rawIdeaArray;
        }

        // Otherwise only specified fields are returned.
        $ideaArray = array();
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $this->attributes())) {
                throw new ActiveRecordException("Requested field {$field} does not belong to idea Model.");
            }
            $ideaArray[$field] = $rawIdeaArray[$field];
        }
        return $ideaArray;
    }

    /**
     * When deleting an Idea, its related Mission or Routine is deleted too.
     * @return bool|void
     * @throws \ActiveRecord\ActiveRecordException
     */
    public function delete()
    {
        if (self::MISSION_TYPE === $this->type)
        {
            $mission = Mission::find_by_pk($this->id);
            $mission->delete();
        } else if (self::ROUTINE_TYPE === $this->type)
        {
            $routine = Routine::find_by_pk($this->id);
            $routine->delete();
        } else {
            throw new ActiveRecordException("Requested Idea to be deleted {$this->id} does not seem to have a Mission or a Routine related.");
        }
        parent::delete();
    }
}