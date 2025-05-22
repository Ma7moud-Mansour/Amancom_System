<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_POST['sim_id'])) {
    echo json_encode(["error" => "Missing SIM ID"]);
    exit;
}

$simId = $_POST['sim_id'];

try {
    $db = (new Database())->connect();

    $stmt = $db->prepare("DELETE FROM sim_assignments WHERE sim_id = ?");
    $success = $stmt->execute([$simId]);

    echo json_encode(["success" => $success]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
