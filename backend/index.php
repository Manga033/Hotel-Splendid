<?php

// Handle CORS preflight requests first
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, Origin, X-Requested-With');
    header('Access-Control-Max-Age: 86400');
    http_response_code(200);
    exit(0);
}

// Set CORS headers for all requests
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
header("Access-Control-Allow-Origin: $origin");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, Origin, X-Requested-With');
header('Access-Control-Max-Age: 86400');

require 'vendor/autoload.php';
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/BookingService.php';
require_once __DIR__ . '/services/BookingRoomsService.php';
require_once __DIR__ . '/services/GuestService.php';
require_once __DIR__ . '/services/ReviewService.php';
require_once __DIR__ . '/services/RoomService.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/roles.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

Flight::register('auth_service', 'AuthService');
Flight::register('bookingService', 'BookingService');
Flight::register('bookingRoomsService', 'BookingRoomsService');
Flight::register('guestService', 'GuestService');
Flight::register('reviewService', 'ReviewService');
Flight::register('roomService', 'RoomService');
Flight::register('auth_middleware', 'AuthMiddleware');

// Global authentication middleware
Flight::route('/*', function () {
    $url = Flight::request()->url;

    // Skip authentication for login and register routes
    if (strpos($url, '/auth/login') === 0 || strpos($url, '/auth/register') === 0) {
        return true;
    }

    try {
        // Get all headers
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = null;

        // Try to get token from Authorization header (standard)
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
            $token = $headers['authorization'];
        } 
        // Fallback to Authentication header (non-standard but your code uses it)
        elseif (isset($headers['Authentication'])) {
            $token = $headers['Authentication'];
        } elseif (isset($headers['authentication'])) {
            $token = $headers['authentication'];
        }
        // Check server variables if headers not found
        elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        // Try Flight's request header method as last resort
        else {
            $token = Flight::request()->getHeader('Authorization');
            if (!$token) {
                $token = Flight::request()->getHeader('Authentication');
            }
        }

        // Remove "Bearer " prefix if present
        if ($token) {
            $token = preg_replace('/^Bearer\s+/i', '', trim($token));
        }

        // Verify the token
        if (!$token || trim($token) === '') {
            Flight::json(['message' => 'Missing authentication token'], 401);
            return false;
        }

        // Call the middleware to verify token
        $verified = Flight::auth_middleware()->verifyToken($token);
        
        if (!$verified) {
            Flight::json(['message' => 'Invalid token'], 401);
            return false;
        }
        
        return true;
        
    } catch (\Exception $e) {
        Flight::json(['message' => $e->getMessage()], 401);
        return false;
    }
}, true);

// Load all routes
require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/BookingRoute.php';
require_once __DIR__ . '/routes/BookingRoomsRoute.php';
require_once __DIR__ . '/routes/GuestRoute.php';
require_once __DIR__ . '/routes/ReviewRoute.php';
require_once __DIR__ . '/routes/RoomRoute.php';

Flight::start();
?>