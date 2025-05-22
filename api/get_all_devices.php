<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();

    $stmt = $db->query("
        SELECT d.serial_number, d.device_type, d.status, d.purchase_date, d.customer_id,
               c.name AS customer_name
        FROM devices d
        LEFT JOIN customers c ON d.customer_id = c.customer_id
        ORDER BY d.serial_number DESC
    ");

    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($devices);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
