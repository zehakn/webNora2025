<?php
require_once 'rest/dao/BaseDAO.php';

class UserDAO extends BaseDAO {
    public function __construct() {
        parent::__construct("User", "UserID");
    }

    public function getByEmail($email) {
        return $this->getByField('Email', $email);
    }

    public function createUser($username, $password, $fullName, $email) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->create([
            'Username' => $username,
            'Password' => $hashedPassword,
            'FullName' => $fullName,
            'Email' => $email,
            'CreatedAt' => date("Y-m-d H:i:s")
        ]);
    }

    public function getAllUsers() {
        return $this->fetchAll();
    }

    public function updateUser($userID, $username, $fullName, $email) {
        return $this->update($userID, [
            'Username' => $username,
            'FullName' => $fullName,
            'Email' => $email
        ]);
    }

    public function updatePassword($userID, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->update($userID, ['Password' => $hashedPassword]);
    }

    public function deleteUser($userID) {
        return $this->delete($userID);
    }
}
?>
