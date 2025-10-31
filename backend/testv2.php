<?php
require_once 'dao/GuestDao.php';
require_once 'dao/RoomDao.php';
require_once 'dao/BookingDao.php';
require_once 'dao/BookingRoomsDao.php';
require_once 'dao/ReviewDao.php';

// Instantiate DAOs
$guestDao = new GuestDao();
$roomDao = new RoomDao();
$bookingDao = new BookingDao();
$bookingRoomsDao = new BookingRoomsDao();
$reviewDao = new ReviewDao();

// Insert sample data
$guestDao->insert([
    'first_name' => 'Michael',
    'last_name'  => 'Dolk',
    'dob'        => '1990-01-01',
    'gender'     => 'male',
    'email'      => 'michael.dolk@example.com',
    'username'   => 'michaeldolk',
    'password'   => password_hash('password123', PASSWORD_DEFAULT),
    'tel_num'    => '+38761111111',
    'country'    => 'Bosnia and Herzegovina',
    'city'       => 'Sarajevo',
    'address'    => 'Example Street 123'
]);

$roomDao->insert([
    'room_number' => '557',
    'floor'       => 9,
    'type'        => 'double',
    'base_price'  => 150.00,
    'description' => 'Test room description.',
    'status'      => 'available'
]);

$bookingDao->insert([
    'guest_id'        => 1,
    'check_in_date'   => date('Y-m-d', strtotime('+1 day')),
    'check_out_date'  => date('Y-m-d', strtotime('+3 days')),
    'num_of_guests'   => 2,
    'num_of_children' => 0,
    'type'            => 'corporate',
    'total_price'     => 300.00,
    'status'          => 'confirmed'
]);

$bookingRoomsDao->insert([
    'booking_id'  => 4,
    'room_id'     => 2,
    'nightly_rate'=> 190.00
]);

$reviewDao->insert([
    'guest_id' => 1,
    'rating'   => 5,
    'title'    => 'Great staff',
    'comment'  => 'Everything was excellent.'
]);

// Retrieve and print all records
$guests = $guestDao->getAll();
print_r($guests);

$rooms = $roomDao->getAll();
print_r($rooms);

$bookings = $bookingDao->getAll();
print_r($bookings);

$bookingRooms = $bookingRoomsDao->getAll();
print_r($bookingRooms);

$reviews = $reviewDao->getAll();
print_r($reviews);

?>