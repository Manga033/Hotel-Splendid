<?php
require_once __DIR__ . '/../dao/GuestDao.php';
require_once __DIR__ . '/BaseService.php';

class GuestService extends BaseService {
    public function __construct() {
        $dao = new GuestDao();
        parent::__construct($dao);
    }

    public function registerGuest($data) {
        $required = ['first_name', 'last_name', 'email', 'username', 'password'];

        foreach($required as $field) {
            if(empty($data[$field])) {
                throw new Exception("$field is required to register a guest.");
            }
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if(strlen($data['password']) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }

        $existing = $this->dao->getGuestByEmail($data['email']);
        if(!empty($existing)) {
            throw new Exception("A guest with this email already exists.");
        }

        return $this->dao->createGuest($data);
    }

    public function getGuestByEmail($email) {
        if(empty($email)) {
            throw new Exception("Email is required to fetch guest details.");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        return $this->dao->getGuestByEmail($email);
    }

}
