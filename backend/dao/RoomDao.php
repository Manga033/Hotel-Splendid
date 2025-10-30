<?php
require_once __DIR__ . '/BaseDao.php';

class RoomDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('rooms');
    }

    public function createRoom($room)
    {
        $data = [
            'room_number' => $room['room_number'],
            'floor' => $room['floor'],
            'type' => $room['type'] ?? 'single',
            'base_price' => $room['base_price'] ?? 0.00,
            'description' => $room['description'],
            'status' => $room['status'] ?? 'available'
        ];
        return $this->insert($data);
    }

    public function getAllRooms()
    {
        return $this->getAll();
    }

    public function getRoomById($id)
    {
        return $this->getById($id);
    }

    public function updateRoom($id, $room)
    {
        $data = [
            'room_number' => $room['room_number'],
            'floor' => $room['floor'],
            'type' => $room['type'],
            'base_price' => $room['base_price'],
            'description' => $room['description'],
            'status' => $room['status']
        ];
        return $this->update($id, $data);
    }

    public function deleteRoom($id)
    {
        return $this->delete($id);
    }
}
?>