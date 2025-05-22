<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing customer ID"]);
    exit;
}

$customerId = $_GET['id'];

try {
    $db = (new Database())->connect();

    // Get customer info
    $stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = ?");
    $stmt->execute([$customerId]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo json_encode(["error" => "Client not found"]);
        exit;
    }

    // Get all devices either directly or indirectly linked to the customer
    $stmt = $db->prepare("
        SELECT DISTINCT d.serial_number, d.device_type, d.status
        FROM devices d
        LEFT JOIN sim_assignments s ON s.device_serial = d.serial_number
        WHERE d.customer_id = :id OR s.customer_id = :id
    ");
    $stmt->execute(['id' => $customerId]);
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Get all SIMs linked via sim_assignments
    $stmt = $db->prepare("
        SELECT s.sim_id, l.line_number, l.provider, l.activation_date, l.status
        FROM sim_assignments s
        JOIN sim_lines l ON s.sim_id = l.sim_id
        WHERE s.customer_id = ?
    ");
    $stmt->execute([$customerId]);
    $sims = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dummy subscriptions (until you build actual subscription logic)
    $subscriptions = []; // you can fill this with test data or real queries

    echo json_encode([
        "client" => $client,
        "devices" => $devices,
        "sims" => $sims,
        "subscriptions" => $subscriptions
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
