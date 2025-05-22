<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();

    $stmt = $db->prepare("INSERT INTO users (user_id, username, password, role) VALUES (?, ?, ?, ?)");
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $success = $stmt->execute([
        $_POST['user_id'],
        $_POST['username'],
        $_POST['password'],
        $_POST['role']
    ]);

    echo json_encode(["success" => $success]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>