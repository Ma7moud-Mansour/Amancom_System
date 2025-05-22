<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();
    $stmt = $db->prepare("INSERT INTO devices (serial_number, device_type, status, purchase_date)
                          VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['serial_number'],
        $_POST['device_type'],
        $_POST['status'],
        $_POST['purchase_date']
    ]);
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
