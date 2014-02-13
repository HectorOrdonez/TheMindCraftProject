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
use engine\Session;
use engine\drivers\Exception;

class SettingsService extends Service
{
    const USER_FIELD_USERNAME = 'username';
    const USER_FIELD_PASSWORD = 'password';

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
        $user = User::find_by_pk($userId);

        if (is_null($user)) {
            throw new Exception('The user you are trying to modify does not exist.');
        }

        switch ($type)
        {
            case self::USER_FIELD_USERNAME:
                $this->updateUsername($user, $newValue);
                break;
            case self::USER_FIELD_PASSWORD:
                $this->updatePassword($user, $newValue);
                break;
            default:
                throw new Exception('Unexpected setting.');
        }
    }

    /**
     * @param User $user
     * @param $newUsername
     */
    private function updateUsername(User $user, $newUsername)
    {
        $user->username = $newUsername;
        Session::set('userName', $user->username);
        $user->save();
        
    }

    /**
     * @param User $user
     * @param $newPassword
     */
    private function updatePassword(User $user, $newPassword)
    {
        $user->password = Encrypter::encrypt($newPassword);
        $user->save();
    }
}