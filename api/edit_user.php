<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();

    if (!empty($_POST['password'])) {
        $stmt = $db->prepare("UPDATE users SET user_id=?, username=?, password=?, role=? WHERE user_id=?");
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $success = $stmt->execute([
            $_POST['user_id'],
            $_POST['username'],
            $_POST['password'],
            $_POST['role'],
            $_POST['user_id']
        ]);
    } else {
        $stmt = $db->prepare("UPDATE users SET user_id=?, username=?, role=? WHERE user_id=?");
        $success = $stmt->execute([
            $_POST['user_id'],
            $_POST['username'],
            $_POST['password'],
            $_POST['role'],
            $_POST['user_id']
        ]);
    }

    echo json_encode(["success" => $success]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>