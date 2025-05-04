<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function create($data) {
        if (empty($data['email']) || empty($data['password']) || empty($data['username']) || empty($data['fullName'])) {
            throw new Exception("Email, password, username, and full name are required.");
        }

        $existingUser = $this->dao->getByField('Username', $data['username']);
        if ($existingUser) {
            throw new Exception("Username already exists.");
        }

        $existingEmail = $this->dao->getByField('Email', $data['email']);
        if ($existingEmail) {
            throw new Exception("Email already exists.");
        }

        return $this->dao->createUser($data['username'], $data['password'], $data['fullName'], $data['email']);
    }

    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function getById($id) {
        $user = $this->dao->getById($id);
        if (!$user) {
            throw new Exception("User not found.");
        }
        return $user;
    }

    public function update($id, $data) {
        if (empty($data['email']) || empty($data['username']) || empty($data['fullName'])) {
            throw new Exception("Email, username, and full name are required.");
        }

        $existingUser = $this->dao->getById($id);
        if (!$existingUser) {
            throw new Exception("User not found.");
        }

        $userWithSameUsername = $this->dao->getByField('Username', $data['username']);
        if ($userWithSameUsername && $userWithSameUsername['UserID'] != $id) {
            throw new Exception("Username already exists.");
        }

        $userWithSameEmail = $this->dao->getByField('Email', $data['email']);
        if ($userWithSameEmail && $userWithSameEmail['UserID'] != $id) {
            throw new Exception("Email already exists.");
        }

        $user = $this->dao->updateUser($id, $data['username'], $data['fullName'], $data['email']);
        if (!$user) {
            throw new Exception("User update failed.");
        }

        return $user;
    }

    public function delete($id) {
        
        $existingUser = $this->dao->getById($id);
        if (!$existingUser) {
            throw new Exception("User not found.");
        }

        return $this->dao->deleteUser($id);
    }
}
