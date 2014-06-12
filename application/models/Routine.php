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
use engine\drivers\Exception;

/**
 * Class Routine
 * @package application\models
 *
 * Magic methods
 * @method static Routine find_by_pk(\int $id) Returns the Routine with given idea_id
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
 * @property \DateTime $last_update Last time this routine created an action.
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
            'last_update' => (is_null($this->last_update)) ? '' : $this->last_update->format('d/m/Y')
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

    /**
     * Checks if this routine is apt for generating new actions.
     * A routine is apt to generate new actions when last update is one week old or more.
     *
     * @return bool
     */
    public function isGenerationNeeded()
    {
        $date = new \DateTime();
        $date->modify('+1 week');

        // In case the last_update of this routine was last week or sooner, check is not necessary.
        if (
            is_null($this->last_update) OR
            $this->last_update <= $date
        ) {
            return true;
        }
        return false;
    }

    /**
     * Goes from today till two weeks ahead, checking if any of those days needs an action from this routine.
     */
    public function generateActions()
    {
        $twoWeeksAhead = new \DateTime();
        $twoWeeksAhead->modify('+2 weeks');

        // The starting date to generate actions is last_update date's next day, or if it is null, today.
        if (is_null($this->last_update)) {
            $date = new \DateTime();
        } else {
            $date = $this->last_update;
            $date->modify('+1 day');
        }

        // While date is before two weeks ahead
        while ($date <= $twoWeeksAhead) {
            if ($this->needsActionDate($date)) {
                $this->createAction($date);
            }

            $date->modify('+1 day');
        }

        // Deducting one day from last date checked, to store it as last_update.
        $date->modify('-1 day');

        // Updating last_update
        $this->last_update = $date->format('Y-m-d');
        $this->save();
    }

    /**
     * Creates an action with creation and to do date with given date parameter, and every other property as a copy
     * of this routine.
     * @param \DateTime $date
     */
    private function createAction(\DateTime $date)
    {
        Action::create(array(
            'user_id' => $this->idea->user_id,
            'routine_id' => $this->idea_id,
            'title' => $this->idea->title,
            'date_creation' => date('Y-m-d'),
            'date_todo' => $date->format('Y-m-d'),
            'time_from' => $this->time_from,
            'time_till' => $this->time_till,
            'important' => $this->idea->important,
            'urgent' => $this->idea->urgent,
        ));
    }

    /**
     * Given a date, checks if this routine needs an action.
     * @param \DateTime $date
     * @return bool
     */
    private function needsActionDate(\DateTime $date)
    {
        // Check if current date is before routine starts
        if (
            false === is_null($this->date_start) AND
            $date < $this->date_start
        ) {
            return false;
        }

        // Check if current date is after routine finishes
        if (
            false === is_null($this->date_finish) AND
            $date > $this->date_finish
        ) {
            return false;
        }

        // Check if date matches the routine's weekdays frequency 
        if (
            false === self::isDayMatchForWeekdays($date->format('l'), $this->frequency_days)
        ) {
            return false;
        }

        // Check if routine's week repetition matches with amount of weeks passed since routine started
        if (false === self::isWeekMatchForWeekRepetition($date)) {
            return false;
        }
        return true;
    }

    /**
     * Receives a specific day in english text format
     * Returns if the day matches this routine day frequency.
     * @param $day
     * @return bool
     * @throws \engine\drivers\Exception
     */
    private function isDayMatchForWeekdays($day)
    {
        $weekdays = str_split($this->frequency_days);

        switch ($day) {
            case 'Monday':
                return ($weekdays[0] == true);
                break;
            case 'Tuesday':
                return ($weekdays[1] == true);
                break;
            case 'Wednesday':
                return ($weekdays[2] == true);
                break;
            case 'Thursday':
                return ($weekdays[3] == true);
                break;
            case 'Friday':
                return ($weekdays[4] == true);
                break;
            case 'Saturday':
                return ($weekdays[5] == true);
                break;
            case 'Sunday':
                return ($weekdays[6] == true);
                break;
            default:
                throw new Exception ('Unexpected Day type : ' . $day, Exception::FATAL_EXCEPTION);
        }
    }

    /**
     * Receives a specific date.
     * Given the date in which this routine is desired to start or, in case the start is not set, the date in which
     * this routine was created, the amount of lapsed weeks is calculated and compared with the desired week repetition.
     * @param \DateTime $currentDate
     * @return bool
     */
    private function isWeekMatchForWeekRepetition(\DateTime $currentDate)
    {
        // Notice that, when date_start is null, the week repetition is checked with the date in which this routine was created.
        $startingDate = is_null($this->date_start) ? $this->idea->date_creation : $this->date_start;

        $daysPassed = $currentDate->diff($startingDate)->days;
        $weeks = floor($daysPassed / 7);
        return (0 == $weeks % $this->frequency_weeks);
    }

    /**
     * Method called when a Routine is modified and therefore its future actions might need to be regenerated.
     * This method deletes all future actions related to this Routine.
     */
    public function resetActions()
    {
        /**
         * @var Action[] $actions
         */
        $actions = Action::find('all', array(
                'conditions' => array(
                    'routine_id = ? AND 
                    date_todo >= DATE(NOW())',
                    $this->idea_id
                ))
        );
        
        foreach($actions as $action)
        {
            $action->delete();
        }
    }
}