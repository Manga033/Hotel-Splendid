<?php
header('Content-Type: application/json');

function fail($msg, $code = 400) {
  http_response_code($code);
  echo json_encode(['ok' => false, 'error' => $msg], JSON_PRETTY_PRINT);
  exit;
}
function ok($data) {
  echo json_encode(['ok' => true, 'data' => $data], JSON_PRETTY_PRINT);
  exit;
}

try {
  // Load config + DAOs (corrected paths)
  // Load configuration and DAO classes. The DAO classes live in the same
  // directory as this test file. We avoid referencing a non-existent
  // 'dao' subfolder, as the files are placed at the project root.
  // Load configuration and DAO classes. The config file sits in the same
  // directory as this script, while the DAO classes live under the
  // 'dao' subdirectory. Using __DIR__ ensures the correct relative path
  // regardless of the current working directory when invoking the script.
  // require_once __DIR__ . '/config.php'; // database connection helper
  require_once __DIR__ . '/dao/BaseDao.php';
  require_once __DIR__ . '/dao/BookingDao.php';
  require_once __DIR__ . '/dao/RoomDao.php';
  require_once __DIR__ . '/dao/ReviewDao.php';

  // Optional BookingRoomsDao
  $bookingRoomsDao = null;
  // Attempt to load the optional BookingRooms DAO from the same directory
  $bookingRoomsPath = __DIR__ . '/dao/BookingRoomsDao.php';
  if (file_exists($bookingRoomsPath)) {
    require_once $bookingRoomsPath;
    $bookingRoomsDao = new BookingRoomsDao();
  }

  $bookingDao = new BookingDao();
  $roomDao    = new RoomDao();
  $reviewDao  = new ReviewDao();

  $action = $_GET['action'] ?? null;
  if (!$action) {
    fail("Missing 'action' query param. See usage header in this file.");
  }

  // Read JSON body if present (tolerate charset in header)
  $raw  = file_get_contents('php://input') ?: '';
  $isJson = isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
  $json = $raw ? json_decode($raw, true) : null;
  if ($raw && $isJson && $json === null) {
    fail('Invalid JSON body.');
  }

  switch ($action) {

    // ---- BOOKINGS ----
    case 'createBooking': {
      if (!$json) {
        // Friendly default if body not provided
        $json = [
          'guest_id'        => 1,
          'check_in_date'   => date('Y-m-d'),
          'check_out_date'  => date('Y-m-d', strtotime('+2 day')),
          'num_of_guests'   => 2,
          'num_of_children' => 0,
          'type'            => 'standard',
          'total_price'     => 200.00
        ];
      }
      $created = $bookingDao->createBooking($json);
      ok($created);
      break;
    }

    case 'listBookings': {
      ok($bookingDao->getAllBookings());
      break;
    }

    case 'getBooking': {
      $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
      if ($id <= 0) fail('Provide ?id=');
      ok($bookingDao->getBookingById($id));
      break;
    }

    case 'updateBooking': {
      $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
      if ($id <= 0) fail('Provide ?id=');
      if (!$json) fail('Provide JSON body with fields to update.');
      ok($bookingDao->updateBooking($id, $json));
      break;
    }

    case 'deleteBooking': {
      $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
      if ($id <= 0) fail('Provide ?id=');
      ok($bookingDao->deleteBooking($id));
      break;
    }

    // ---- ROOMS ----
    case 'updateRoomPrice': {
      $roomId  = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
      $newPrice = isset($_GET['base_price']) ? (float)$_GET['base_price'] : null;
      if ($roomId <= 0 || $newPrice === null) fail('Provide ?room_id= and ?base_price=');
      // Use the generic update() method from BaseDao to modify only the base_price.
      // updateRoom() expects all room fields and would trigger undefined index
      // notices if we provided only a subset, so we call update() directly.
      ok($roomDao->update($roomId, ['base_price' => $newPrice]));
      break;
    }

    // ---- REVIEWS ----
    case 'deleteReview': {
      $reviewId = isset($_GET['review_id']) ? (int)$_GET['review_id'] : 0;
      if ($reviewId <= 0) fail('Provide ?review_id=');
      ok($reviewDao->deleteReview($reviewId));
      break;
    }

    // ---- BOOKING_ROOMS (optional) ----
    case 'addBookingRoom': {
      if (!$bookingRoomsDao) fail('BookingRoomsDao.php not present in project.');
      if (!$json) fail('Provide JSON: { "booking_id":..., "room_id":..., "nightly_rate":... }');
      $data = [
        'booking_id'   => (int)$json['booking_id'],
        'room_id'      => (int)$json['room_id'],
        'nightly_rate' => (float)$json['nightly_rate']
      ];
      ok($bookingRoomsDao->insert($data));
      break;
    }

    default:
      fail("Unknown action '{$action}'.");
  }

} catch (Throwable $e) {
  fail($e->getMessage(), 500);
}
?>