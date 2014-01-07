<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table idea.
 * Date: 07/01/14 02:30
 */

namespace application\models;

use \ActiveRecord\Model as Model;

class Idea extends Model
{
    public static $table_name = 'idea'; // Table name
    
    static $belongs_to = array(
        array('user')
    );
}