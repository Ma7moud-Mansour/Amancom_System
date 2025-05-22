<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = (new Database())->connect();

        $stmt = $db->prepare("
            INSERT INTO subscriptions (server_name, customer_id, amount, duration, start_date, renewal_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $success = $stmt->execute([
            $_POST['server_name'],    // device_serial
            $_POST['customer_id'],    // customer_id
            $_POST['amount'],         // amount
            $_POST['duration'],       // duration (Monthly / Yearly)
            $_POST['start_date'],     // start_date
            $_POST['renewal_date'],   // renewacl_date
            $_POST['status']          // status (Active / Pending / Expired)
        ]);

        echo json_encode(["success" => $success]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
