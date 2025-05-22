<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();
    $stmt = $db->prepare("DELETE FROM devices WHERE serial_number = ?");
    $stmt->execute([$_POST['serial_number']]);
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
