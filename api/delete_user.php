<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();

    $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
    $success = $stmt->execute([$_POST['id']]);

    echo json_encode(["success" => $success]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>