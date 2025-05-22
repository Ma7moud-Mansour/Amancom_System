<?php
require_once 'Database.php';

class Payment {
    private $conn;
    private $table = "payments";

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function addPayment($id, $invoiceId, $amount, $method, $date, $proof) {
        $sql = "INSERT INTO " . $this->table . " (payment_id, invoice_id, amount, payment_method, payment_date, proof_url)
                VALUES (:id, :invoiceId, :amount, :method, :date, :proof)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':invoiceId' => $invoiceId,
            ':amount' => $amount,
            ':method' => $method,
            ':date' => $date,
            ':proof' => $proof
        ]);
    }
}
?>