<?php
// Handle CORS preflight requests first
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, Origin, X-Requested-With');
    header('Access-Control-Max-Age: 86400');
    http_response_code(200);
    exit(0);
}

// Set CORS headers for all requests
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, Origin, X-Requested-With');

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

Flight::register('auth_service', 'AuthService');
Flight::register('bookingService', 'BookingService');
Flight::register('bookingRoomsService', 'BookingRoomsService');
Flight::register('guestService', 'GuestService');
Flight::register('reviewService', 'ReviewService');
Flight::register('roomService', 'RoomService');
Flight::register('auth_middleware', 'AuthMiddleware');

Flight::before('start', function(&$params, &$output){
    $url = Flight::request()->url;
    
    if (strpos($url, '/auth/login') === 0 || 
        strpos($url, '/auth/register') === 0 ||
        strpos($url, '/test/simple') === 0) {
        return;
    }

    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $token = null;
    
    if (isset($headers['Authorization'])) {
        $token = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $token = $headers['authorization'];
    } elseif (isset($headers['Authentication'])) {
        $token = $headers['Authentication'];
    } elseif (isset($headers['authentication'])) {
        $token = $headers['authentication'];
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
    }
    
    if ($token) {
        $token = preg_replace('/^Bearer\s+/i', '', trim($token));
    }

    if (!$token || trim($token) === '') {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['message' => 'Missing authentication token']);
        exit();
    }
    
    try {
        $verified = Flight::auth_middleware()->verifyToken($token);
        if (!$verified) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token']);
            exit();
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['message' => $e->getMessage()]);
        exit();
    }
});

require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/BookingRoute.php';
require_once __DIR__ . '/routes/BookingRoomsRoute.php';
require_once __DIR__ . '/routes/GuestRoute.php';
require_once __DIR__ . '/routes/ReviewRoute.php';
require_once __DIR__ . '/routes/RoomRoute.php';

Flight::start();