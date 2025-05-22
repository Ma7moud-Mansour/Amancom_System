<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();
    $stmt = $db->query("SELECT sim_id, line_number, provider, activation_date, status FROM sim_lines");
    $sims = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sims);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
