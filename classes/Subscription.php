<?php
require_once 'Database.php';

class Subscription {
    private $conn;
    private $table = "subscriptions";

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function addSubscription($id, $serverName, $customerId, $amount, $duration, $startDate, $renewalDate, $status) {
        $sql = "INSERT INTO " . $this->table . " (subscription_id, server_name, customer_id, amount, duration, start_date, renewal_date, status)
                VALUES (:id, :serverName, :customerId, :amount, :duration, :startDate, :renewalDate, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':serverName' => $serverName,
            ':customerId' => $customerId,
            ':amount' => $amount,
            ':duration' => $duration,
            ':startDate' => $startDate,
            ':renewalDate' => $renewalDate,
            ':status' => $status
        ]);
    }
}
?>