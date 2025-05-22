<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();
    $stmt = $db->prepare("INSERT INTO sim_lines (line_number, provider, activation_date, status) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([
        $_POST['line_number'],
        $_POST['provider'],
        $_POST['activation_date'],
        $_POST['status']
    ]);
    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
