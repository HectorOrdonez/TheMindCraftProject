<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table action.
 * Date: 07/01/14 02:30
 */

namespace application\models;

use \ActiveRecord\Model as Model;

class Action extends Model
{
    public static $table_name = 'action'; // Table name
    
    static $has_one = array(
        array('idea')
    );
}