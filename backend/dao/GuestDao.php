<?php
require_once __DIR__ . '/BaseDao.php';


class GuestDao extends BaseDao {
   public function __construct() {
       parent::__construct("guests");
   }

   public function createGuest($guest) 
   {
        $data = [
            'first_name' => $guest['first_name'],
            'last_name'  => $guest['last_name'],
            'dob' => $guest['dob'],
            'gender' => $guest['gender'],
            'email' => $guest['email'],
            'username' => $guest['username'],
            'password' => password_hash($guest['password'], PASSWORD_BCRYPT),
            'tel_num' => $guest['tel_num'],
            'country' => $guest['country'],
            'city' => $guest['city'],
            'address' => $guest['address']
        ];

        return $this->insert($data);
   }

    public function getAllGuests() 
    {
         return $this->getAll();
    }

    public function getGuestById($id) 
    {
         return $this->getById($id);
    }

    public function updateGuest($id, $guest) 
    {
        $data = [
            'first_name' => $guest['first_name'],
            'last_name'  => $guest['last_name'],
            'dob' => $guest['dob'],
            'gender' => $guest['gender'],
            'email' => $guest['email'],
            'username' => $guest['username'],
            'password' => password_hash($guest['password'], PASSWORD_BCRYPT),
            'tel_num' => $guest['tel_num'],
            'country' => $guest['country'],
            'city' => $guest['city'],
            'address' => $guest['address']
        ];

        return $this->update($id, $data);
    }

    public function deleteGuest($id) 
    {
         return $this->delete($id);
    }
}
?>
