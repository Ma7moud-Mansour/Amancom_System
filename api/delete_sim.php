<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();
    $stmt = $db->prepare("DELETE FROM sim_lines WHERE sim_id = ?");
    $success = $stmt->execute([$_POST['id']]);
    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
