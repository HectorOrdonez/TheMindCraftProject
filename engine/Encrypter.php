<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Library to centralize the Encrypting methods. Using password_hash and password_verify from PHP Core.
 * http://nl3.php.net/manual/en/function.password-hash.php
 * http://nl3.php.net/manual/en/function.password-verify.php
 * @date: 14/06/13 16:30
 */

namespace engine;

/**
 * Class Encrypter
 * @package engine
 */
class Encrypter
{
    /**
     * Algorithmic cost to be used-
     * @var int
     */
    private static $cost = 9;

    /**
     * Encrypting method.
     * @param string $string The Password sent by the user without encryption.
     * @return string $hashedPassword The password sent by the user after encryption
     */
    public static function encrypt($string)
    {
        $encryptingOptions = array(
            'cost' => self::$cost
        );

        return password_hash($string, PASSWORD_DEFAULT, $encryptingOptions);
    }

    /**
     * Verifying method.
     * @param string $string
     * @param string $hashedPassword
     * @return bool
     */
    public static function verify($string, $hashedPassword)
    {
        return password_verify($string, $hashedPassword);
    }
}