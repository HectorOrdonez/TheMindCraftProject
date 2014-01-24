<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table regular_idea.
 * Date: 24/01/14 17:45
 */

namespace application\models;

use \ActiveRecord\Model as Model;

class RegularIdea extends Model
{
    public static $table_name = 'regular_idea'; // Table name
    
    static $belongs_to = array(
        array('user')
    );
}