<?php
require_once __DIR__ . '/../dao/BookingRoomsDao.php';
require_once __DIR__ . '/BaseService.php';

class BookingRoomsService extends BaseService {
    public function __construct() {
        $dao = new BookingRoomsDao();
        parent::__construct($dao);
    }

    public function addRoomToBooking($data) {
        if(empty($data['booking_id']) || empty($data['room_id'])) {
            throw new Exception("Booking ID and Room ID are required to add a room to a booking.");
        }

        if(!isset($data['nightly_rate']) || $data['nightly_rate'] === '') {
            $data['nightly_rate'] = 0.00;
        }

        if(!is_numeric($data['nightly_rate']) || $data['nightly_rate'] < 0) {
            throw new Exception("Nightly rate must be a non-negative number.");
        }

        return $this->dao->createBookingRooms($data);
    }

    public function listBookingRoomsByNightlyRate() {
        return $this->dao->listByNightlyRate();
    }
}
?>