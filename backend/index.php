<?php
require 'vendor/autoload.php'; //run autoloader


require_once __DIR__ . '/services/BookingService.php';
Flight::register('bookingService', 'BookingService');

require_once __DIR__ . '/services/BookingRoomsService.php';
Flight::register('bookingRoomsService', 'BookingRoomsService');

require_once __DIR__ . '/services/GuestService.php';
Flight::register('guestService', 'GuestService');

require_once __DIR__ . '/services/ReviewService.php';
Flight::register('reviewService', 'ReviewService');

require_once __DIR__ . '/services/RoomService.php';
Flight::register('roomService', 'RoomService');

require_once __DIR__ . '/routes/BookingRoute.php';
require_once __DIR__ . '/routes/BookingRoomsRoute.php';
require_once __DIR__ . '/routes/GuestRoute.php';
require_once __DIR__ . '/routes/ReviewRoute.php';
require_once __DIR__ . '/routes/RoomRoute.php';

Flight::start();  //start FlightPHP
?>
