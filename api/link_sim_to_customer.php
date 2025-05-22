<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_POST['sim_id']) || !isset($_POST['customer_id'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

$simId = $_POST['sim_id'];
$customerId = $_POST['customer_id'];

try {
    $db = (new Database())->connect();

    // Check if already assigned
    $check = $db->prepare("SELECT * FROM sim_assignments WHERE sim_id = ?");
    $check->execute([$simId]);
    if ($check->fetch()) {
        echo json_encode(["error" => "SIM is already assigned"]);
        exit;
    }

    // Insert assignment
    $stmt = $db->prepare("INSERT INTO sim_assignments (sim_id, customer_id) VALUES (?, ?)");
    $success = $stmt->execute([$simId, $customerId]);

    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["error" => $errorInfo[2]]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
