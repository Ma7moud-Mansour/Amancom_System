<?php
session_start();

// حذف كل بيانات الجلسة
session_unset();
session_destroy();

// إعادة التوجيه لصفحة تسجيل الدخول
header("Location: ../public/Login.php");
exit;
?>
