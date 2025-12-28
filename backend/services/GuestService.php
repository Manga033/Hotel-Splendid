<?php
require_once __DIR__ . '/../dao/GuestDao.php';
require_once __DIR__ . '/BaseService.php';

class GuestService extends BaseService {
    public function __construct() {
        $dao = new GuestDao();
        parent::__construct($dao);
    }

    public function registerGuest($data) {
        $errors = [];

        if (!isset($data['first_name']) || empty(trim($data['first_name']))) {
            $errors['first_name'] = 'First name is required';
        } else {
            $firstName = trim($data['first_name']);
            
            if (strlen($firstName) < 2) {
                $errors['first_name'] = 'First name must be at least 2 characters';
            }
            
            if (strlen($firstName) > 50) {
                $errors['first_name'] = 'First name cannot exceed 50 characters';
            }
            
            if (!preg_match('/^[a-zA-Z\s\'-]+$/u', $firstName)) {
                $errors['first_name'] = 'First name can only contain letters, spaces, hyphens, and apostrophes';
            }
        }

        if (!isset($data['last_name']) || empty(trim($data['last_name']))) {
            $errors['last_name'] = 'Last name is required';
        } else {
            $lastName = trim($data['last_name']);
            
            if (strlen($lastName) < 2) {
                $errors['last_name'] = 'Last name must be at least 2 characters';
            }
            
            if (strlen($lastName) > 50) {
                $errors['last_name'] = 'Last name cannot exceed 50 characters';
            }
            
            if (!preg_match('/^[a-zA-Z\s\'-]+$/u', $lastName)) {
                $errors['last_name'] = 'Last name can only contain letters, spaces, hyphens, and apostrophes';
            }
        }

        if (!isset($data['email']) || empty(trim($data['email']))) {
            $errors['email'] = 'Email is required';
        } else {
            $email = trim($data['email']);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            }
            
            if (strlen($email) > 150) {
                $errors['email'] = 'Email cannot exceed 150 characters';
            }
 
            if (empty($errors['email'])) {
                $existing = $this->dao->getGuestByEmail($email);
                if (!empty($existing)) {
                    $errors['email'] = 'A guest with this email already exists';
                }
            }
        }

        if (!isset($data['username']) || empty(trim($data['username']))) {
            $errors['username'] = 'Username is required';
        } else {
            $username = trim($data['username']);
            
            if (strlen($username) < 3) {
                $errors['username'] = 'Username must be at least 3 characters';
            }
            
            if (strlen($username) > 50) {
                $errors['username'] = 'Username cannot exceed 50 characters';
            }
            
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
                $errors['username'] = 'Username can only contain letters, numbers, underscores, and dashes';
            }
        }

        if (!isset($data['password']) || empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } else {
            $password = $data['password'];
            
            if (strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            if (strlen($password) > 100) {
                $errors['password'] = 'Password cannot exceed 100 characters';
            }
        }

        if (isset($data['dob']) && !empty($data['dob'])) {
            try {
                $dob = new DateTime($data['dob']);
                $today = new DateTime();
                $age = $today->diff($dob)->y;
                
                if ($age < 18) {
                    $errors['dob'] = 'Guest must be at least 18 years old';
                }
                
                if ($age > 120) {
                    $errors['dob'] = 'Invalid date of birth';
                }
            } catch (Exception $e) {
                $errors['dob'] = 'Invalid date format';
            }
        }

        if (isset($data['gender']) && !empty($data['gender'])) {
            $allowedGenders = ['male', 'female', 'prefer_not_to_say'];
            if (!in_array($data['gender'], $allowedGenders)) {
                $errors['gender'] = 'Invalid gender value';
            }
        }

        if (isset($data['tel_num']) && !empty($data['tel_num'])) {
            $phone = preg_replace('/[^0-9+]/', '', $data['tel_num']);
            
            if (strlen($phone) < 10) {
                $errors['tel_num'] = 'Phone number must be at least 10 digits';
            }
            
            if (strlen($phone) > 20) {
                $errors['tel_num'] = 'Phone number cannot exceed 20 characters';
            }
        }

        if (isset($data['country']) && !empty($data['country'])) {
            if (strlen($data['country']) > 100) {
                $errors['country'] = 'Country name cannot exceed 100 characters';
            }
        }

        if (isset($data['city']) && !empty($data['city'])) {
            if (strlen($data['city']) > 100) {
                $errors['city'] = 'City name cannot exceed 100 characters';
            }
        }

        if (isset($data['address']) && !empty($data['address'])) {
            if (strlen($data['address']) > 255) {
                $errors['address'] = 'Address cannot exceed 255 characters';
            }
        }

        if (!empty($errors)) {
            throw new Exception(json_encode([
                'validation_failed' => true,
                'errors' => $errors
            ]));
        }

        $sanitizedData = [
            'first_name' => htmlspecialchars(trim($data['first_name']), ENT_QUOTES, 'UTF-8'),
            'last_name' => htmlspecialchars(trim($data['last_name']), ENT_QUOTES, 'UTF-8'),
            'email' => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
            'username' => htmlspecialchars(trim($data['username']), ENT_QUOTES, 'UTF-8'),
            'password' => $data['password'], // Will be hashed in DAO
            'dob' => isset($data['dob']) ? $data['dob'] : null,
            'gender' => isset($data['gender']) ? $data['gender'] : null,
            'tel_num' => isset($data['tel_num']) ? preg_replace('/[^0-9+]/', '', $data['tel_num']) : null,
            'country' => isset($data['country']) ? htmlspecialchars(trim($data['country']), ENT_QUOTES, 'UTF-8') : null,
            'city' => isset($data['city']) ? htmlspecialchars(trim($data['city']), ENT_QUOTES, 'UTF-8') : null,
            'address' => isset($data['address']) ? htmlspecialchars(trim($data['address']), ENT_QUOTES, 'UTF-8') : null
        ];

        return $this->dao->createGuest($sanitizedData);
    }

    public function getGuestByEmail($email) {
        if (empty($email)) {
            throw new Exception("Email is required to fetch guest details");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        return $this->dao->getGuestByEmail($email);
    }
}