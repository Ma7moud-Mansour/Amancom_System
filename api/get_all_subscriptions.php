<?php
require_once '../classes/Database.php';
header("Content-Type: application/json");

try {
    $db = (new Database())->connect();

    // استعلام لجلب بيانات الاشتراكات مع اسم العميل
    $query = "
        SELECT 
            s.subscription_id,
            s.server_name,
            s.customer_id,
            c.username AS customer_name,
            s.amount,
            s.duration,
            s.start_date,
            s.renewal_date,
            s.status
        FROM subscriptions s
        JOIN customers c ON s.customer_id = c.customer_id
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subscriptions);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
