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

class User extends Model
{
    public static $table_name = 'user'; // Table name

    static $has_many = array(
        array('idea', 'class_name' => 'Idea'),
        array('routine', 'class_name' => 'Routine')
    );
}