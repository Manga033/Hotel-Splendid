<?php
require_once __DIR__ . '/../dao/RoomDao.php';
require_once __DIR__ . '/BaseService.php';

class RoomService extends BaseService {
    public function __construct() {
        $dao = new RoomDao();
        parent::__construct($dao);
    }

    public function isRoomAvailable($roomId) {
        if (empty($roomId)) {
            throw new Exception("Room ID is required to check availability");
        }

        return $this->dao->isRoomAvailable($roomId);
    }

    public function ensureRoomAvailable($roomId) {
        if (!$this->isRoomAvailable($roomId)) {
            throw new Exception("The room with ID $roomId is not available");
        }
    }

    public function getByStatus($status) {
        $allowed = ['available', 'occupied', 'maintenance'];

        if (!in_array($status, $allowed)) {
            throw new Exception("Invalid room status: $status");
        }
        
        return $this->dao->getByStatus($status);
    }


    public function createRoom($data) {
        $errors = [];

        if (!isset($data['room_number']) || empty(trim($data['room_number']))) {
            $errors['room_number'] = 'Room number is required';
        } else {
            $roomNumber = trim($data['room_number']);
            
            if (strlen($roomNumber) > 10) {
                $errors['room_number'] = 'Room number cannot exceed 10 characters';
            }
        }

        if (isset($data['floor']) && $data['floor'] !== '') {
            if (!is_numeric($data['floor'])) {
                $errors['floor'] = 'Floor must be a number';
            } elseif ($data['floor'] < 0 || $data['floor'] > 100) {
                $errors['floor'] = 'Floor must be between 0 and 100';
            }
        }

        if (isset($data['type']) && !empty($data['type'])) {
            $allowedTypes = ['single', 'double', 'suite', 'deluxe', 'family'];
            if (!in_array($data['type'], $allowedTypes)) {
                $errors['type'] = 'Invalid room type';
            }
        }

        if (isset($data['base_price']) && $data['base_price'] !== '') {
            if (!is_numeric($data['base_price']) || $data['base_price'] < 0) {
                $errors['base_price'] = 'Base price must be a non-negative number';
            } elseif ($data['base_price'] > 10000) {
                $errors['base_price'] = 'Base price cannot exceed 10000';
            }
        }

        if (isset($data['status']) && !empty($data['status'])) {
            $allowedStatuses = ['available', 'occupied', 'maintenance'];
            if (!in_array($data['status'], $allowedStatuses)) {
                $errors['status'] = 'Invalid room status';
            }
        }

        if (isset($data['description']) && !empty($data['description'])) {
            if (strlen($data['description']) > 1000) {
                $errors['description'] = 'Description cannot exceed 1000 characters';
            }
        }

        if (!empty($errors)) {
            throw new Exception(json_encode([
                'validation_failed' => true,
                'errors' => $errors
            ]));
        }

        return $this->dao->createRoom($data);
    }
}
?>
