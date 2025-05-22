<?php
session_start();
header("Content-Type: application/json");

// تأكد إن اليوزر داخل فعلاً
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

echo json_encode(["role" => $_SESSION['user']['role']]);
