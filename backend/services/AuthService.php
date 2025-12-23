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

            if (empty($entity['username']) || empty($entity['password'])) {
                return ['success' => false, 'error' => 'Username and password are required.'];
            }
            
            if (isset($entity['username'])) {
                $entity['username'] = trim($entity['username']);
            }

            $username_exists = $this->auth_dao->get_user_by_username($entity['username']);
            if ($username_exists) {
                return ['success' => false, 'error' => 'Username already registered.'];
            }

            if (empty($entity['role'])) {
                $entity['role'] = 'user';
            }

            $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

            $ok = parent::create($entity);
            if (!$ok) return ['success'=>false,'error'=>'User could not be created.'];       

            $new_id = $this->auth_dao->lastInsertId();
            $user = $this->auth_dao->getById($new_id);

            if (!$user) {
                return ['success' => false, 'error' => 'User created but could not be fetched.'];
            }

            unset($user['password']);
            return ['success' => true, 'data' => $user];
        }

        public function login($entity) {

            if (empty($entity['username']) || empty($entity['password'])) {
                return ['success' => false, 'error' => 'Username and password are required.'];
            }

            $username = trim($entity['username']);
            $user = $this->auth_dao->get_user_by_username($username);

            if (!$user) {
                return ['success' => false, 'error' => 'Invalid username or password.'];
            }

            if (!$user || !password_verify($entity['password'], $user['password'])) {
                return ['success' => false, 'error' => 'Invalid username or password.'];
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

            $token = JWT::encode(
                $jwt_payload,
                Config::JWT_SECRET(),
                'HS256'
            );

            return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
        }
    }
    