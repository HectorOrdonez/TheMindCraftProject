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
 * @property \DateTime $last_update Last time this routine created an action.
 */
class Routine extends Model
{
    const SELECTED_TRUE = 1;
    const SELECTED_FALSE = 0;

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

    public function generateAction()
    {
        $isMatch = Routine::isNewActionRequired(
            \DateTime::createFromFormat('Y-m-d', $this->idea->date_creation->format('Y-m-d')),
            (is_null($this->date_start)) ? '' : \DateTime::createFromFormat('Y-m-d', $this->date_start->format('Y-m-d')),
            (is_null($this->date_finish)) ? '' : \DateTime::createFromFormat('Y-m-d', $this->date_finish->format('Y-m-d')),
            $this->frequency_days,
            $this->frequency_weeks,
            (is_null($this->last_update)) ? '' : \DateTime::createFromFormat('Y-m-d', $this->last_update->format('Y-m-d'))
        );

        if ($isMatch) {
            Action::create(array(
                'user_id' => $this->idea->user_id,
                'routine_id' => $this->idea_id,
                'title' => $this->idea->title,
                'date_creation' => date('Y-m-d'),
                'date_todo' => date('Y-m-d'),
                'time_from' => $this->time_from,
                'time_till' => $this->time_till,
                'important' => $this->idea->important,
                'urgent' => $this->idea->urgent,
            ));
            $this->last_update = date('Y-m-d');
        } else {
            if ('' == $this->last_update OR $this->last_update->format('Y-m-d') != date('Y-m-d')) {
                $this->last_update = date('Y-m-d');
            }
        }

        if (self::SELECTED_TRUE == $this->idea->selected) {
            $this->idea->selected = self::SELECTED_FALSE;
            $this->idea->save();
        }
        $this->save();

    }

    private static function isNewActionRequired($dateCreation, $dateStart, $dateFinish, $frequencyDays, $frequencyWeeks, $lastUpdate)
    {
        $today = new \DateTime();

        // In case the last_update of this routine was today, check is not necessary.
        if ('' != $lastUpdate AND $lastUpdate == $today) {
            return false;
        }

        // Check if current date is before routine starts
        if ('' != $dateStart AND $today < $dateStart) {
            return false;
        }

        // Check if current date is after routine finishes
        if ('' != $dateFinish and $today > $dateFinish) {
            return false;
        }

        // Check if today's day matches the routine's weekdays frequency 
        if (false === self::isDayMatchForWeekdays($today->format('l'), $frequencyDays)) {
            return false;
        }

        // Check if routine's week repetition matches with amount of weeks passed since routine started
        $daysPassed = ('' == $dateStart)? $today->diff($dateCreation)->days : $today->diff($dateStart)->days;
        $weeks = floor($daysPassed / 7);
        if (false === self::isWeekMatchForWeekRepetition($weeks, $frequencyWeeks)) {
            return false;
        }
        return true;
    }

    private static function isDayMatchForWeekdays($day, $weekdays)
    {
        $weekdays = str_split($weekdays);

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
                throw new Exception ('Unexpected Day type : ' . $day);
        }
    }

    private static function isWeekMatchForWeekRepetition($weeks, $frequencyWeeks)
    {
        return (0 == $weeks % $frequencyWeeks);
    }
}