<?php
session_start();

if (!isset($_SESSION['user'])) {
  http_response_code(401); // Unauthorized
  echo json_encode(["error" => "Unauthorized"]);
  exit;
}

// رجع بيانات المستخدم
echo json_encode([
  "user_id" => $_SESSION['user']['user_id'],
  "name" => $_SESSION['user']['name'],
  "email" => $_SESSION['user']['email'],
  "role" => $_SESSION['user']['role']
]);
