<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Model that manages the interactions to the table user.
 * Date: 23/07/13 10:30
 */

namespace application\models;

use application\engine\Model;
use engine\Encrypter;
use engine\Exception;

class UserModel extends Model
{
    /**
     * Key of the userFields array that relates to the password. Used to unset it.
     * @var int
     */
    static private $_passwordFieldKey = 2;
    /**
     * Fields of the Table User
     * @var array
     */
    protected $userFields = array(
        'id',
        'name',
        'password',
        'role',
        'last_login'
    );

    /**
     * User Model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Receives a User name and its password. Verifies if these parameters are right and, if so, returns its data.
     *
     * @param string $userName
     * @param string $password Unencrypted password.
     * @return mixed array/bool User data if found, false if not.
     */
    public function selectUserForLogin($userName, $password)
    {
        $conditions = array(
            'name' => $userName
        );

        $result = $this->db->select('user', $this->userFields, $conditions);

        if (count($result) != 0) {
            if (Encrypter::verify($password, $result[0]['password']) === TRUE) {
                // User is logged in.
                $userData = $result[0];
                return $userData;
            }
        }
        return FALSE;
    }

    /**
     * Selects all Users
     * @return array of Users.
     */
    public function selectAll()
    {
        $fields = $this->userFields;

        unset($fields[self::$_passwordFieldKey]);

        $result = $this->db->select('user', $fields);

        return $result;
    }

    /**
     * Collects the data from a specific user.
     *
     * @param int $userId User Id.
     * @return array User data
     */
    public function selectById($userId)
    {
        $fields = $this->userFields;
        unset($fields[self::$_passwordFieldKey]);

        $conditions = array(
            'id' => $userId
        );

        $result = $this->db->select('user', $fields, $conditions);

        if (count($result) > 0) {
            return $result[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Creates user with the specified parameters.
     *
     * @param string $userName
     * @return string New User id
     */
    public function insert($userName)
    {
        $valuesArray = array('name' => $userName);

        $this->db->insert('user', $valuesArray);

        return $this->db->lastInsertId();
    }

    /**
     * Updates user with the data sent in the array newData.
     * The newData contains the fields and values to update. Notice that:
     * 1 - Column Id cannot be modified.
     * 2 - Column Password will be encrypted.
     *
     * @param $userId int - Id of the User to update.
     * @param $newData array - Fields to update and their new values.
     * @throws Exception
     */
    public function update($userId, $newData)
    {
        $setArray = array();

        foreach ($newData as $setField => $setValue) {
            if (!in_array($setField, $this->userFields)) {
                throw new Exception('Error in the update of the table user. The field ' . $setField . ' does not belong to this model.');
            }

            if ($setField == 'id') {
                throw new Exception('User table column id cannot be modified.');
            }

            if ($setField == 'password') {
                $setArray[$setField] = Encrypter::encrypt($setValue);
            } else {
                $setArray[$setField] = $setValue;
            }
        }

        $conditionsArray = array(
            'id' => $userId
        );

        $this->db->update('user', $setArray, $conditionsArray);
    }

    /**
     * Deletes user.
     *
     * @param $userId int - Id of the user to delete.
     */
    public function delete($userId)
    {
        $conditionsArray = array(
            'id' => $userId
        );

        $this->db->delete('user', $conditionsArray);
    }
}