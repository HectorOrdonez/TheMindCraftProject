<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Library that manages the Settings page's logic.
 * Date: 25/07/13 01:30
 */

namespace application\libraries;

use application\engine\Library;
use application\models\UserModel;
use engine\Exception;
use engine\Session;

class SettingsLibrary extends Library
{
    /**
     * Defining $_model Model type.
     * @var UserModel $_model
     */
    protected $_model;

    /**
     * Library constructor of Settings logic.
     */
    public function __construct()
    {
        parent::__construct(new UserModel);
    }

    public function updateSetting($userId, $type, $newValue)
    {
        $user = $this->_model->selectById($userId);

        if ($user === FALSE) {
            throw new Exception('The user you are trying to modify does not exist.');
        }

        switch ($type) {
            case 'name':
                if ($newValue == $user['name']) {
                    throw new Exception('This edition request is not changing any user data.');
                } else {
                    Session::set('userName', $newValue);
                }
                break;
            case 'password':
                break;
            default:
                throw new Exception('Setting type ' . $type . ' cannot be updated.');
        }

        $this->_model->update($userId, array($type => $newValue));
    }
}