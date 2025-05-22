<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing SIM ID"]);
    exit;
}

$simId = $_GET['id'];

try {
    $db = (new Database())->connect();

    // Get SIM details
    $stmt = $db->prepare("SELECT * FROM sim_lines WHERE sim_id = ?");
    $stmt->execute([$simId]);
    $sim = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sim) {
        echo json_encode(["error" => "SIM not found"]);
        exit;
    }

    // Get linked customer if any
    $stmt = $db->prepare("
        SELECT c.customer_id, c.name, c.contact_info
        FROM sim_assignments a
        JOIN customers c ON a.customer_id = c.customer_id
        WHERE a.sim_id = ?
        LIMIT 1
    ");
    $stmt->execute([$simId]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "sim" => $sim,
        "customer" => $customer ?: null
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
