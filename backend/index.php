<?php
// Handle CORS for all requests
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, Origin, X-Requested-With');
    header('Access-Control-Max-Age: 86400');
    http_response_code(200);
    exit(0);
}

header("Access-Control-Allow-Origin: $origin");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, Origin, X-Requested-With');

// PHP Built-in Server Routing
// Only process PHP files and API routes, serve static files directly
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files directly
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot|map)$/', $path)) {
    return false; // Let PHP built-in server handle static files
}

// Serve index.html for root and hash routes
if ($path === '/' || $path === '' || !preg_match('/^\/[a-z]/', $path)) {
    // Check if frontend/index.html exists
    if (file_exists(__DIR__ . '/frontend/index.html')) {
        // Set correct content type
        header('Content-Type: text/html; charset=UTF-8');
        readfile(__DIR__ . '/frontend/index.html');
        exit(0);
    } else if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html; charset=UTF-8');
        readfile(__DIR__ . '/index.html');
        exit(0);
    }
}

// Now handle API routes with Flight
require 'vendor/autoload.php';

require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/BookingService.php';
require_once __DIR__ . '/services/BookingRoomsService.php';
require_once __DIR__ . '/services/GuestService.php';
require_once __DIR__ . '/services/ReviewService.php';
require_once __DIR__ . '/services/RoomService.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

Flight::register('authService', 'AuthService');
Flight::register('bookingService', 'BookingService');
Flight::register('bookingRoomsService', 'BookingRoomsService');
Flight::register('guestService', 'GuestService');
Flight::register('reviewService', 'ReviewService');
Flight::register('roomService', 'RoomService');
Flight::register('auth_middleware', 'AuthMiddleware');

Flight::set('flight.base_url', '/');

require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/BookingRoute.php';
require_once __DIR__ . '/routes/GuestRoute.php';
require_once __DIR__ . '/routes/ReviewRoute.php';
require_once __DIR__ . '/routes/RoomRoute.php';

Flight::route('GET /ping', function () {
  Flight::json(['ok' => true, 'time' => date('c')]);
});
Flight::start();
?>