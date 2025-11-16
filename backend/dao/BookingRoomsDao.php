<?php
require_once __DIR__ . '/BaseDao.php';

class BookingRoomsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('booking_rooms');
    }

    public function createBookingRooms($bookingRooms)
    {
        $data = [
            'booking_id' => $bookingRooms['booking_id'],
            'room_id' => $bookingRooms['room_id'],
            'nightly_rate' => $bookingRooms['nightly_rate'] ?? 0.00
        ];
        return $this->insert($data);
    }

    public function getAllBookingRooms()
    {
        return $this->getAll();
    }

    public function getBookingRoomsById($id)
    {
        return $this->getById($id);
    }

    public function updateBookingRooms($id, $bookingRooms)
    {
        $data = [
            'booking_id' => $bookingRooms['booking_id'],
            'room_id' => $bookingRooms['room_id'],
            'nightly_rate' => $bookingRooms['nightly_rate']
        ];
        return $this->update($id, $data);
    }

    public function deleteBookingRooms($id)
    {
        return $this->delete($id);
    }

    public function listByNightlyRate() {
        $sql = "SELECT br.*, r.*
                FROM booking_rooms br
                JOIN rooms r ON r.id = br.room_id
                ORDER BY br.nightly_rate DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>