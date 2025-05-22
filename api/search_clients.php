<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

$query = $_GET['query'] ?? '';

try {
    $db = (new Database())->connect();
    $stmt = $db->prepare("SELECT customer_id, name, contact_info FROM customers WHERE name LIKE ? OR contact_info LIKE ? LIMIT 10");
    $like = "%$query%";
    $stmt->execute([$like, $like]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
