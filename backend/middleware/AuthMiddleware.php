<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function verifyToken($token){
        if(!$token || trim($token) === '') {
            throw new Exception("Missing authentication token");
        }

        // Remove Bearer prefix if present (just in case)
        $token = preg_replace('/^Bearer\s+/i', '', trim($token));
        
        try {
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            
            // Cast the user object to array
            $user = (array) $decoded_token->user;
            
            // Store user and token in Flight registry
            Flight::set('user', (object)$user);
            Flight::set('jwt_token', $token);
            
            return TRUE;
        } catch (\Firebase\JWT\ExpiredException $e) {
            throw new Exception("Token has expired");
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            throw new Exception("Invalid token signature");
        } catch (\Firebase\JWT\BeforeValidException $e) {
            throw new Exception("Token not yet valid");
        } catch (Exception $e) {
            throw new Exception("Invalid token: " . $e->getMessage());
        }
    }

    public function authorizeRole($requiredRole) {
        $user = Flight::get('user');

        if(!$user) {
            Flight::json(['message' => 'Unauthenticated'], 401);
            exit();
        }

        if ($user->role !== $requiredRole) {
            Flight::json(['message' => 'Access denied: insufficient privileges'], 403);
            exit();
        }
    }

    public function authorizeRoles($roles) {
        $user = Flight::get('user');

        if (!$user) {
            Flight::json(['message' => 'Unauthenticated'], 401);
            exit();
        } 

        if (!in_array($user->role, $roles)) {
            Flight::json(['message' => 'Forbidden: role not allowed'], 403);
            exit();
        }
    }

    function authorizePermission($permission) {
        $user = Flight::get('user');

        if(!$user) {
            Flight::json(['message' => 'Unauthenticated'], 401);
            exit();
        }

        if (!isset($user->permissions) || !in_array($permission, $user->permissions)) {
            Flight::json(['message' => 'Access denied: permission missing'], 403);
            exit();
        }
    }   
}