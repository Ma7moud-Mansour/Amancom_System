<?php
require_once 'Database.php';

class Client {
    private $conn;
    private $table = "customers";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function addClient($id, $name, $type, $contact, $status) {
        $sql = "INSERT INTO " . $this->table . " (customer_id, name, customer_type, contact_info, account_status)
                VALUES (:id, :name, :type, :contact, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':type' => $type,
            ':contact' => $contact,
            ':status' => $status
        ]);
    }

    public function getAllClients() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
