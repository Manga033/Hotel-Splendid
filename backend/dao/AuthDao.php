<?php
require_once __DIR__ . '/BaseDao.php';


class AuthDao extends BaseDao {
   protected $table_name;


   public function __construct() {
       $this->table_name = "users";
       parent::__construct($this->table_name);
   }


   public function get_user_by_username($username) {
       $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username LIMIT 1"
        );
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   public function lastInsertId() {
    return $this->connection->lastInsertId();
}
}
