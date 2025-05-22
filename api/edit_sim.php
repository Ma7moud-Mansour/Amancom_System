<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();
    $stmt = $db->prepare("UPDATE sim_lines SET line_number = ?, provider = ?, activation_date = ?, status = ? WHERE sim_id = ?");
    $success = $stmt->execute([
        $_POST['line_number'],
        $_POST['provider'],
        $_POST['activation_date'],
        $_POST['status'],
        $_POST['id']
    ]);
    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
