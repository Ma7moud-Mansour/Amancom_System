<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function registerUser($id, $username, $password, $role) {
        $sql = "INSERT INTO " . $this->table . " (user_id, username, password, role)
                VALUES (:id, :username, :password, :role)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':role' => $role
        ]);
    }

    public function loginUser($username, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>