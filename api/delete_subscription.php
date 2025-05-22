<?php
// delete_subscription.php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subscription_id = $_POST['id'] ?? null;

    if (!$subscription_id) {
        echo json_encode(["error" => "Missing subscription ID"]);
        exit;
    }

    try {
        $db = (new Database())->connect();
        $stmt = $db->prepare("DELETE FROM subscriptions WHERE subscription_id = ?");
        $success = $stmt->execute([$subscription_id]);

        echo json_encode(["success" => $success]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

?>