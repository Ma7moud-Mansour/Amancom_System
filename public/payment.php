<?php
session_start();

// لو مش عامل تسجيل دخول يرجعه للوجين
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit;
}

// تحقق من صلاحيات الدور
$role = $_SESSION['user']['role'];
if ($role !== 'Owner' && $role !== 'Admin') {
    // لو مش Owner ولا Admin يتم منعه
    echo "🚫 لا تملك صلاحية الوصول لهذه الصفحة.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة المدفوعات - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/Dashboard.css">
  <link rel="stylesheet" href="../Style/Payments.css">
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
      <h1>إدارة المدفوعات</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <div class="top-bar">
      <input type="text" placeholder="ابحث برقم المعاملة أو اسم العميل..." />
      <button id="addPaymentBtn">➕ إضافة مدفوعات</button>
    </div>

    <section class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>رقم المعاملة</th>
            <th>اسم العميل</th>
            <th>المبلغ المدفوع</th>
            <th>التاريخ</th>
            <th>طريقة الدفع</th>
            <th>الخيارات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>TX-1001</td>
            <td>أحمد عبدالسلام</td>
            <td>500 جنيه</td>
            <td>2025-01-01</td>
            <td>Visa</td>
            <td>
              <button class="edit">✏️</button>
              <button class="delete">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <div class="modal hidden" id="paymentModal">
      <form>
        <h2>إضافة مدفوعات جديدة</h2>
        <label>اسم العميل:</label>
        <input type="text" required />
        
        <label>المبلغ المدفوع:</label>
        <input type="number" required />
        
        <label>طريقة الدفع:</label>
        <select required>
          <option>كاش</option>
          <option>تحويل بنكي</option>
          <option>Visa</option>
          <option>MasterCard</option>
        </select>

        <label>تاريخ الدفع:</label>
        <input type="date" required />

        <div class="modal-actions">
          <button type="submit" class="save">حفظ</button>
          <button type="button" id="cancelPaymentModal" class="cancel">إلغاء</button>
        </div>
      </form>
    </div>
  </main>

  <script src="../JS/Payments.js"></script>
</body>
</html>
