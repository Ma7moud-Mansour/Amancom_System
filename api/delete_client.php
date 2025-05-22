<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    $db = (new Database())->connect();
    $stmt = $db->prepare("DELETE FROM customers WHERE customer_id = :id");
    $success = $stmt->execute([':id' => $id]);
    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
