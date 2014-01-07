<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Settings page's logic.
 * Date: 25/07/13 01:30
 */

namespace application\services;

use application\engine\Service;
use application\models\User;
use engine\Encrypter;
use engine\drivers\Exception;

class SettingsService extends Service
{
    /**
     * Service constructor of Settings logic.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function updateSetting($userId, $type, $newValue)
    {
        /**
         * @var User $user
         */
        $user = User::find_by_id($userId);

        if (is_null($user)) {
            throw new Exception('The user you are trying to modify does not exist.');
        }

        if ($type == 'password') {
            $newValue = Encrypter::encrypt($newValue);
        }
        
        $user->$type = $newValue;
        $user->save();
    }
}