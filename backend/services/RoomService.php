<?php
require_once __DIR__ . '/../dao/RoomDao.php';
require_once __DIR__ . '/BaseService.php';

class RoomService extends BaseService {
    public function __construct() {
        $dao = new RoomDao();
        parent::__construct($dao);
    }

    public function isRoomAvailable($roomId) {
        if(empty($roomId)) {
            throw new Exception("Room ID is required to check availability.");
        }


        return $this->dao->isRoomAvailable($roomId);
    }

    public function ensureRoomAvailable($roomId) {
        if(!$this->isRoomAvailable($roomId)) {
            throw new Exception("The room with ID $roomId is not available.");
        }
    }

    public function getByStatus($status) {
        $allowed = ['available', 'occupied', 'maintenance'];

        if(!in_array($status, $allowed)) {
            throw new Exception("Invalid room status: $status.");
        }
        return $this->dao->getByStatus($status);
    }

    public function createRoom($data) {
        if(empty($data['room_number'])) {
            throw new Exception("Room number is required to create a room.");
        }

        if(isset($data['base_price']) && $data['base_price'] < 0) {
            throw new Exception("Base price cannot be negative.");
        }

        if(isset($data['status'])) {
            $allowed = ['available', 'occupied', 'maintenance'];
            if(!in_array($data['status'], $allowed)) {
                throw new Exception("Invalid room status: " . $data['status']);
            }
        }
        return $this->dao->createRoom($data);
    }
}
