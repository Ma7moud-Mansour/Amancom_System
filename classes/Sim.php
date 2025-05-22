<?php
require_once 'Database.php';

class Sim {
    private $conn;
    private $table = "sim_lines";

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function addSim($id, $lineNumber, $provider, $lineType, $status, $sellDate, $expDate, $actDate) {
        $sql = "INSERT INTO " . $this->table . " (sim_id, line_number, provider, line_type, status, sell_date, expiration_date, activation_date)
                VALUES (:id, :lineNumber, :provider, :lineType, :status, :sellDate, :expDate, :actDate)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':lineNumber' => $lineNumber,
            ':provider' => $provider,
            ':lineType' => $lineType,
            ':status' => $status,
            ':sellDate' => $sellDate,
            ':expDate' => $expDate,
            ':actDate' => $actDate
        ]);
    }
}
?>