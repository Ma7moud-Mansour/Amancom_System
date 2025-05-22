<?php
require_once '../classes/Client.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = uniqid("CUST");
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $status = $_POST['status'] ?? 'Active';

    $client = new Client();
    $success = $client->addClient($id, $name, $type, $contact, $status);
    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
