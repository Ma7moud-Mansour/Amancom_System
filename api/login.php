<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../classes/Database.php';
session_start();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(["error" => "اسم المستخدم وكلمة المرور مطلوبة"]);
    exit;
}

try {
    $db = (new Database())->connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
        $_SESSION['user'] = [
            "user_id" => $user['user_id'],
            "username" => $user['username'],
            "role" => $user['role']
        ];
        

        echo json_encode([
            "success" => true,
            "user" => [
                "user_id" => $user['user_id'],
                "username" => $user['username'],
                "role" => $user['role']
            ]
        ]);
    } else {
        echo json_encode(["error" => "اسم المستخدم أو كلمة المرور غير صحيحة"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
