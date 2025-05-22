<?php
require_once 'Database.php';

class Device {
    private $conn;
    private $table = "devices";

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function addDevice($serial, $type, $status, $purchaseDate) {
        $sql = "INSERT INTO " . $this->table . " (serial_number, device_type, status, purchase_date)
                VALUES (:serial, :type, :status, :purchaseDate)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':serial' => $serial,
            ':type' => $type,
            ':status' => $status,
            ':purchaseDate' => $purchaseDate
        ]);
    }
}
?>