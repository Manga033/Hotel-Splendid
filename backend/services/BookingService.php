<?php
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/BaseService.php';

class BookingService extends BaseService {
    public function __construct() {
        $dao = new BookingDao();
        parent::__construct($dao);
    }

    public function createBooking($data) {
        $errors = [];

        if (!isset($data['guest_id']) || empty($data['guest_id'])) {
            $errors['guest_id'] = 'Guest ID is required';
        } elseif (!is_numeric($data['guest_id']) || $data['guest_id'] < 1) {
            $errors['guest_id'] = 'Invalid guest ID';
        }

        if (!isset($data['check_in_date']) || empty($data['check_in_date'])) {
            $errors['check_in_date'] = 'Check-in date is required';
        } else {
            try {
                $checkIn = new DateTime($data['check_in_date']);
                $today = new DateTime();
                $today->setTime(0, 0, 0);
                
                if ($checkIn < $today) {
                    $errors['check_in_date'] = 'Check-in date cannot be in the past';
                }
            } catch (Exception $e) {
                $errors['check_in_date'] = 'Invalid date format';
            }
        }

        if (!isset($data['check_out_date']) || empty($data['check_out_date'])) {
            $errors['check_out_date'] = 'Check-out date is required';
        } else {
            try {
                $checkOut = new DateTime($data['check_out_date']);
                
                if (isset($checkIn) && $checkOut <= $checkIn) {
                    $errors['check_out_date'] = 'Check-out date must be after check-in date';
                }

                if (isset($checkIn)) {
                    $interval = $checkIn->diff($checkOut);
                    if ($interval->days > 90) {
                        $errors['check_out_date'] = 'Booking duration cannot exceed 90 days';
                    }
                }
            } catch (Exception $e) {
                $errors['check_out_date'] = 'Invalid date format';
            }
        }

        if (!isset($data['num_of_guests'])) {
            $data['num_of_guests'] = 1;
        } else {
            if (!is_numeric($data['num_of_guests']) || $data['num_of_guests'] < 1) {
                $errors['num_of_guests'] = 'Must have at least 1 guest';
            } elseif ($data['num_of_guests'] > 10) {
                $errors['num_of_guests'] = 'Maximum 10 guests per booking';
            }
        }

        if (!isset($data['num_of_children'])) {
            $data['num_of_children'] = 0;
        } else {
            if (!is_numeric($data['num_of_children']) || $data['num_of_children'] < 0) {
                $errors['num_of_children'] = 'Invalid number of children';
            } elseif ($data['num_of_children'] > 8) {
                $errors['num_of_children'] = 'Maximum 8 children per booking';
            }
        }

        if (!isset($data['type']) || empty($data['type'])) {
            $data['type'] = 'standard';
        } else {
            $allowedTypes = ['standard', 'nonrefundable', 'corporate', 'agency', 'other'];
            if (!in_array($data['type'], $allowedTypes)) {
                $errors['type'] = 'Invalid booking type';
            }
        }

        if (!empty($errors)) {
            throw new Exception(json_encode([
                'validation_failed' => true,
                'errors' => $errors
            ]));
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

