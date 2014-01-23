<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description: 
 * Service that manages the User Sign Ups 
 * @date 22/01/14 22:00
 */

namespace application\services;

use application\engine\Service;
use application\models\User;
use engine\Encrypter;
use engine\drivers\Exception;

/**
 * Class SignUpService
 * @package application\services
 */
class SignUpService extends Service
{
    const DEFAULT_USER_ROLE = 'basic';
    const DEFAULT_USER_STATE = 'inactive';

    /**
     * SignUp Service constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * SignUp method. Receives a Mail, a Username and a Password, and signs it up to our system.
     * 
     * A Username and a Mail can not be repeated, so this method will look up into Mindcraft Database if they are already in use.
     * If so, throws an exception.
     */
    public function signUp($mail, $username, $password)
    {
        // Check mail availability
        $userWithSameMail = User::find_by_mail($mail);
        
        if (false === is_null($userWithSameMail))
        {
            throw new Exception('Mail already in use.', Exception::GENERAL_EXCEPTION, EXCEPTION_SIGNUP_MAIL_IN_USE);
        }
        
        // Check username availability
        $userWithSameUsername = User::find_by_username($username);

        if (false === is_null($userWithSameUsername))
        {
            throw new Exception('Username already in use.', Exception::GENERAL_EXCEPTION, EXCEPTION_SIGNUP_USERNAME_IN_USE);
        }
        
        // Create new User
        $newUser = User::create(array(
            'username' => $username,
            'password' => Encrypter::encrypt($password),
            'role' => self::DEFAULT_USER_ROLE,
            'state' => self::DEFAULT_USER_STATE
        ));

        $newUser->save();
    }
}