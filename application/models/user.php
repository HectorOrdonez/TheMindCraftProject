<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table user.
 * Date: 07/01/14 02:30
 */

namespace application\models;

use \ActiveRecord\Model as Model;

/**
 * Class User
 * @package application\models
 *
 * Magic methods ...
 *
 * Magically accessed attributes ...
 * @property int id
 * @property string $username
 * @property string $mail
 * @property string $password
 * @property string $role (admin|basic)
 * @property string $state (active|inactive|pending)
 * @property \DateTime $last_login
 */
class User extends Model
{
    public static $table_name = 'user'; // Table name

    static $has_many = array(
        array('idea', 'class_name' => 'Idea'),
        array('mission', 'through' => 'Idea', 'class_name' => 'Mission'),
        array('routine', 'through' => 'Idea', 'class_name' => 'Mission'),
        array('action', 'class_name' => 'Action')
    );
    
}