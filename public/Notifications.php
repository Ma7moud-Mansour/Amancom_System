<?php
session_start();

// لو مش عامل تسجيل دخول يرجعه للوجين
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة التنبيهات - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/Dashboard.css">
  <link rel="stylesheet" href="../Style/Notifications.css">
</head>
<body>
  <aside class="sidebar">
    <h2>لوحة التحكم</h2>
    <a href="Device.php"><i>📦</i> إدارة الأجهزة</a>
    <a href="sim.php"><i>📶</i> إدارة الشرائح</a>
    <a href="Users.php"><i>👥</i> العملاء</a>
    <a href="Subscription.php"><i>💳</i> الاشتراكات</a>
    <a href="payment.php"><i>💰</i> المدفوعات</a>
    <a href="Notifications.php"><i>🔔</i> التنبيهات</a>
    <a href="User_Management.php"><i>🛠️</i> المستخدمين</a>
    <a href="../api/logout.php"><i>🚪</i> تسجيل الخروج</a>
  </aside>

  <main class="main">
    <div class="main-header">
      <h1>إدارة التنبيهات</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <div class="top-bar">
      <select id="filterNotifications">
        <option value="all">كل التنبيهات</option>
        <option value="subscription">انتهاء اشتراك</option>
        <option value="sim">شريحة غير مفعّلة</option>
        <option value="payment">دفعة متأخرة</option>
        <option value="device">جهاز غير مفعل</option>
      </select>
    </div>

    <section class="notifications-list">
      <div class="notification">
        <p>⚠️ انتهاء اشتراك لجهاز GPS-001 بتاريخ 2025-09-01</p>
        <button class="mark-read">✔️ تم القراءة</button>
        <button class="delete">🗑️ حذف</button>
      </div>

      <div class="notification">
        <p>⚠️ شريحة SIM-001245 غير مفعّلة منذ 2025-02-15</p>
        <button class="mark-read">✔️ تم القراءة</button>
        <button class="delete">🗑️ حذف</button>
      </div>

      <div class="notification">
        <p>⚠️ دفعة متأخرة بمبلغ 500 جنيه من أحمد عبدالسلام</p>
        <button class="mark-read">✔️ تم القراءة</button>
        <button class="delete">🗑️ حذف</button>
      </div>
    </section>
  </main>

  <script src="../JS/Notifications.js"></script>
</body>
</html>
