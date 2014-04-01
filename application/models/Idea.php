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
use engine\drivers\Exception;

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
    const TYPE_MISSION = 'mission';
    const TYPE_ROUTINE = 'routine';
    const IMPORTANT_TRUE = 1;
    const IMPORTANT_FALSE = 0;
    const URGENT_TRUE = 1;
    const URGENT_FALSE = 0;
    const SELECTED_TRUE = 1;
    const SELECTED_FALSE = 0;

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
     * Sets the selected state as requested.
     * @param $selectState
     */
    public function setSelect($selectState)
    {
        $this->selected = $selectState;
        $this->save();
    }

    /**
     * When deleting an Idea, its related Mission or Routine is deleted too.
     * @return bool|void
     * @throws \ActiveRecord\ActiveRecordException
     */
    public function delete()
    {
        if (self::TYPE_MISSION === $this->type) {
            $mission = Mission::find_by_pk($this->id);
            $mission->delete();
        } else if (self::TYPE_ROUTINE === $this->type) {
            $routine = Routine::find_by_pk($this->id);
            $routine->delete();
        } else {
            throw new ActiveRecordException("Requested Idea to be deleted {$this->id} does not seem to have a Mission or a Routine related.");
        }
        parent::delete();
    }

    /**
     * Makes sure that this idea is a mission.
     * In case is not, the related routine is deleted and a new mission with same parameters is created.
     *
     * @return Mission Requested Mission object.
     * @throws Exception
     */
    public function convertIdeaToMission()
    {
        if (self::TYPE_MISSION === $this->type) {
            return Mission::find_by_pk($this->id);
        } else if (self::TYPE_ROUTINE === $this->type) {
            $newMission = Mission::create(array('idea_id' => $this->id));

            /**
             * @var Routine $routineToDelete
             */
            $routineToDelete = Routine::find_by_pk($this->id);
            $routineToDelete->delete();

            $this->type = self::TYPE_MISSION;
            $this->save();
            return $newMission;
        } else {
            throw new Exception('Unknown Idea type.');
        }
    }

    /**
     * Makes sure that this idea is a routine.
     * In case is not, the related mission is deleted and a new routine with same parameters is created.
     *
     * @return Routine Requested Routine object.
     * @throws Exception
     */
    public function convertIdeaToRoutine()
    {
        if (self::TYPE_MISSION === $this->type) {
            $newRoutine = Routine::create(array('idea_id' => $this->id));

            /**
             * @var Mission $missionToDelete
             */
            $missionToDelete = Mission::find_by_pk($this->id);
            $missionToDelete->delete();

            $this->type = self::TYPE_ROUTINE;
            $this->save();
            return $newRoutine;
        } else if (self::TYPE_ROUTINE === $this->type) {
            return Routine::find_by_pk($this->id);
        } else {
            throw new Exception('Unknown Idea type.', Exception::FATAL_EXCEPTION);
        }
    }
}