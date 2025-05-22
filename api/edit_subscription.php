<?PHP
// edit_subscription.php
require_once '../classes/Database.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

$subscription_id = $_POST['subscription_id'] ?? null;
$device_serial = $_POST['device_serial'] ?? null;
$customer_id = $_POST['customer_id'] ?? null;
$amount = $_POST['amount'] ?? null;
$duration = $_POST['duration'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$renewal_date = $_POST['renewal_date'] ?? null;
$status = $_POST['status'] ?? null;

if (!$subscription_id || !$device_serial || !$customer_id || !$amount || !$duration || !$start_date || !$renewal_date || !$status) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

try {
    $db = (new Database())->connect();
    $stmt = $db->prepare("UPDATE subscriptions SET server_name = ?, customer_id = ?, amount = ?, duration = ?, start_date = ?, renewal_date = ?, status = ? WHERE subscription_id = ?");
    $success = $stmt->execute([$device_serial, $customer_id, $amount, $duration, $start_date, $renewal_date, $status, $subscription_id]);

    echo json_encode(["success" => $success]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>