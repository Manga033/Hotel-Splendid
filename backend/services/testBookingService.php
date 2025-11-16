<?php
require_once __DIR__ . '/BookingService.php';

$bookingService = new BookingService();

try {
    $new_booking = [
        'guest_id' => 1, 
        'check_in_date' => '2024-07-01',
        'check_out_date' => '2024-07-05',
        'num_of_guests' => 2,
        'num_of_children' => 1,
        'type' => 'deluxe',
        'total_price' => 500.00,
        'status' => 'confirmed'
    ];

    $result = $bookingService->createBooking($new_booking);
    echo "Booking created successfully!\n";
    print_r($result);

    $bookings = $bookingService->getAll();
    echo "All bookings:\n";
    print_r($bookings);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>