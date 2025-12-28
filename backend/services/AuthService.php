<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {

    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct(new AuthDao());
    }

    public function get_user_by_username($username) {
        return $this->auth_dao->get_user_by_username($username);
    }

    public function register($entity) {
        $errors = [];

        if (!isset($entity['username']) || empty(trim($entity['username']))) {
            $errors['username'] = 'Username is required';
        } else {
            $username = trim($entity['username']);
            
            if (strlen($username) < 3) {
                $errors['username'] = 'Username must be at least 3 characters';
            }
            
            if (strlen($username) > 50) {
                $errors['username'] = 'Username cannot exceed 50 characters';
            }
            
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
                $errors['username'] = 'Username can only contain letters, numbers, underscores, and dashes';
            }

            if (empty($errors['username'])) {
                $existing = $this->auth_dao->get_user_by_username($username);
                if ($existing) {
                    $errors['username'] = 'Username already taken';
                }
            }
        }

        if (isset($entity['email']) && !empty($entity['email'])) {
            $email = trim($entity['email']);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            }
        }

        if (!isset($entity['password']) || empty($entity['password'])) {
            $errors['password'] = 'Password is required';
        } else {
            $password = $entity['password'];
            
            if (strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            if (strlen($password) > 100) {
                $errors['password'] = 'Password cannot exceed 100 characters';
            }
        }

        if (isset($entity['role']) && !empty($entity['role'])) {
            $allowedRoles = ['user', 'admin'];
            if (!in_array($entity['role'], $allowedRoles)) {
                $errors['role'] = 'Invalid role. Must be: user or admin';
            }
        }

        if (!empty($errors)) {
            return [
                'success' => false, 
                'error' => 'Validation failed',
                'errors' => $errors
            ];
        }

        $entity['username'] = trim($entity['username']);
        if (isset($entity['email'])) {
            $entity['email'] = filter_var($entity['email'], FILTER_SANITIZE_EMAIL);
        }
        
        if (empty($entity['role'])) {
            $entity['role'] = 'user';
        }

        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

        $ok = parent::create($entity);
        if (!$ok) {
            return ['success' => false, 'error' => 'User could not be created'];
        }

        $new_id = $this->auth_dao->lastInsertId();
        $user = $this->auth_dao->getById($new_id);

        if (!$user) {
            return ['success' => false, 'error' => 'User created but could not be fetched'];
        }

        unset($user['password']);
        return ['success' => true, 'data' => $user];
    }

    public function login($entity) {
        $errors = [];

        if (!isset($entity['username']) || empty(trim($entity['username']))) {
            $errors['username'] = 'Username is required';
        } else {
            $username = trim($entity['username']);
            
            if (strlen($username) < 3) {
                $errors['username'] = 'Invalid username or password';
            }
        }

        if (!isset($entity['password']) || empty($entity['password'])) {
            $errors['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $errors
            ];
        }

        $username = trim($entity['username']);
        $user = $this->auth_dao->get_user_by_username($username);

        if (!$user) {
            return ['success' => false, 'error' => 'Invalid username or password'];
        }

        if (!password_verify($entity['password'], $user['password'])) {
            return ['success' => false, 'error' => 'Invalid username or password'];
        }

        unset($user['password']);

        if (!isset($user['role'])) {
            $user['role'] = 'user';
        }

        $jwt_payload = [
            'user' => $user,
            'iat'  => time(),
            'exp'  => time() + (60 * 60 * 24)
        ];

        $token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');

        return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
    }
}