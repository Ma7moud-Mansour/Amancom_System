<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();

    $stmt = $db->query("SELECT user_id, username, password, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>