<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing device serial"]);
    exit;
}

$serial = $_GET['id'];

try {
    $db = (new Database())->connect();

    // Get device
    $stmt = $db->prepare("SELECT * FROM devices WHERE serial_number = ?");
    $stmt->execute([$serial]);
    $device = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$device) {
        echo json_encode(["error" => "Device not found"]);
        exit;
    }

    // Get related customer
    $customer = null;
    if ($device['customer_id']) {
        $cstmt = $db->prepare("SELECT name, contact_info FROM customers WHERE customer_id = ?");
        $cstmt->execute([$device['customer_id']]);
        $customer = $cstmt->fetch(PDO::FETCH_ASSOC);
    }

    echo json_encode(["device" => $device, "customer" => $customer]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
