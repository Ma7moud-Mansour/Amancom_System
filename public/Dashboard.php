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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>لوحة التحكم - Amancom</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../Style/Dashboard.css" />
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
      <h1>لوحة التحكم</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <section class="stats">
      <div class="card"><h3><i>📡</i> الأجهزة المتاحة</h3><p>250</p></div>
      <div class="card"><h3><i>📲</i> الشرائح المتوفرة</h3><p>180</p></div>
      <div class="card"><h3><i>👤</i> العملاء</h3><p>120</p></div>
      <div class="card alert"><h3><i>⏰</i> الاشتراكات المنتهية</h3><p>15</p></div>
    </section>

    <section class="alerts">
      <h2>التنبيهات</h2>
      <table>
        <tr><th>العميل</th><th>البيان</th><th>التنبيه</th></tr>
        <tr><td>أحمد عبدالسلام</td><td>#4567</td><td>قرب انتهاء اشتراك</td></tr>
        <tr><td>محمود يوسف</td><td>---</td><td>انخفاض في كمية الشرائح</td></tr>
        <tr><td>منة الله محمد</td><td>---</td><td>دفعة لم يتم تسديدها</td></tr>
      </table>
    </section>

    <section class="graph">
      <h2>إحصائيات الاشتراكات</h2>
      <canvas id="subscriptionChart" height="100"></canvas>
    </section>
  </main>

  <script src="../JS/Dashboard.js"></script>
</body>
</html>
