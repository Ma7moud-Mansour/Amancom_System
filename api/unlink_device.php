<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_POST['serial_number'])) {
    echo json_encode(["success" => false, "error" => "Missing serial_number"]);
    exit;
}

try {
    $db = (new Database())->connect();
    $stmt = $db->prepare("UPDATE devices SET customer_id = NULL WHERE serial_number = ?");
    $stmt->execute([$_POST['serial_number']]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
