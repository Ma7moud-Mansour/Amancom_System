<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_POST['serial_number']) || !isset($_POST['customer_id'])) {
    echo json_encode(["success" => false, "error" => "Missing parameters"]);
    exit;
}

try {
    $db = (new Database())->connect();

    // Check if device exists
    $stmt = $db->prepare("SELECT * FROM devices WHERE serial_number = ?");
    $stmt->execute([$_POST['serial_number']]);
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$device) {
        echo json_encode(["success" => false, "error" => "Device not found"]);
        exit;
    }

    // Check if already assigned
    if (!empty($device['customer_id'])) {
        echo json_encode(["success" => false, "error" => "Device already assigned"]);
        exit;
    }

    // Link device
    $stmt = $db->prepare("UPDATE devices SET customer_id = ? WHERE serial_number = ?");
    $stmt->execute([$_POST['customer_id'], $_POST['serial_number']]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
