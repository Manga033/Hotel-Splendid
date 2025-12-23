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

    public function isRoomAvailable($id) {
        $sql = "SELECT status FROM rooms WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        return $room && $room['status'] === 'available';
    }

        public function getByStatus($status) {
        $stmt = $this->connection->prepare("
            SELECT * FROM rooms
            WHERE status = :status
        ");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}