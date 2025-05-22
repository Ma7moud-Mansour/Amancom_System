<?php
require_once '../classes/Database.php';

header("Content-Type: application/json");

try {
    $db = (new Database())->connect();
    $stmt = $db->query("SELECT customer_id, name, customer_type, contact_info, account_status FROM customers");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clients);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
