<?php
require_once __DIR__ . '/BaseDao.php';

class BookingDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('bookings');
    }

    public function createBooking($booking)
    {
        $data = [
            'guest_id'        => $booking['guest_id'],
            'check_in_date'   => $booking['check_in_date'],
            'check_out_date'  => $booking['check_out_date'],
            'num_of_guests'   => $booking['num_of_guests'] ?? 1,
            'num_of_children' => $booking['num_of_children'] ?? 0,
            'type'            => $booking['type'] ?? 'standard'
            // 'total_price'     => $booking['total_price'] ?? 0.00,
            // 'status'         => $booking['status'] ?? 'pending'
        ];
        return $this->insert($data);
    }

    public function getAllBookings()
    {
        return $this->getAll();
    }

    public function getBookingById($id)
    {
        return $this->getById($id);
    }

    public function updateBooking($id, $booking)
    {   
        $data = [
            'guest_id'        => $booking['guest_id'],
            'check_in_date'   => $booking['check_in_date'],
            'check_out_date'  => $booking['check_out_date'],
            'num_of_guests'   => $booking['num_of_guests'],
            'num_of_children' => $booking['num_of_children'],
            'type'            => $booking['type'],
            'total_price'     => $booking['total_price'],
            'status'         => $booking['status']
        ];
        return $this->update($id, $data);
    }

    public function deleteBooking($id)
    {
        return $this->delete($id);
    }

    public function listByCreatedAt() {
        $sql = "SELECT * FROM bookings ORDER BY created_at DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingsByGuestId($guest_id) {
    $stmt = $this->connection->prepare("SELECT * FROM " . $this->table . " WHERE guest_id = :guest_id");
    $stmt->execute(['guest_id' => $guest_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
