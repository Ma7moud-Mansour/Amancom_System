<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $status = $_POST['status'] ?? 'Active';

    $db = (new Database())->connect();
    $stmt = $db->prepare("UPDATE customers SET name = :name, customer_type = :type, contact_info = :contact, account_status = :status WHERE customer_id = :id");
    $success = $stmt->execute([
        ':name' => $name,
        ':type' => $type,
        ':contact' => $contact,
        ':status' => $status,
        ':id' => $id
    ]);
    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
