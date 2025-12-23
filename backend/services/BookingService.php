<?php
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/BaseService.php';

class BookingService extends BaseService {
    public function __construct() {
        $dao = new BookingDao();
        parent::__construct($dao);
    }

    public function createBooking($data) {
        if (empty($data['guest_id']) || empty($data['check_in_date']) || empty($data['check_out_date'])) {
        throw new Exception("Guest ID, Check-in-date and Check-out-date are required to create a booking.");
    }

        if (strtotime($data['check_in_date']) >= strtotime($data['check_out_date'])) {
        throw new Exception("Check-out date must be later than Check-in date.");
    }

        return $this->dao->createBooking($data);
    }

public function listBookingsByCreatedAt() {
        return $this->dao->listByCreatedAt();
    }

    public function getBookingsByGuestId($guest_id) {
    return $this->dao->getBookingsByGuestId($guest_id);
}
}
