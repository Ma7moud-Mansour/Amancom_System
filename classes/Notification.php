<?php
require_once 'Database.php';

class Notification {
    private $conn;
    private $table = "notifications";

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function sendNotification($id, $type, $message, $recipient, $timestamp, $status) {
        $sql = "INSERT INTO " . $this->table . " (notification_id, type, message, recipient_username, timestamp, status)
                VALUES (:id, :type, :message, :recipient, :timestamp, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':type' => $type,
            ':message' => $message,
            ':recipient' => $recipient,
            ':timestamp' => $timestamp,
            ':status' => $status
        ]);
    }
}
?>