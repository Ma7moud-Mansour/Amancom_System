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
  <title>ملف العميل - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/ClientProfile.css">
  <link rel="stylesheet" href="../Style/Dashboard.css">
  <style>
    .hidden {
      display: none !important;
    }
    .modal {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal form {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 100%;
      max-width: 500px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }
  </style>
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
      <h1>ملف العميل</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <section class="profile-details">
      <h2>بيانات العميل</h2>
      <p><strong>الاسم:</strong> <span id="client-name">جار التحميل...</span></p>
      <p><strong>الهاتف:</strong> <span id="client-contact">جار التحميل...</span></p>
      <p><strong>الحالة:</strong> <span id="client-status">جار التحميل...</span></p>
      <div class="profile-actions">
        <button class="edit" onclick="openEditModal()">✏️ تعديل</button>
        <?php
        // تحقق من صلاحيات الدور
        $role = $_SESSION['user']['role'];
        if ($role == 'Owner' || $role == 'Admin') {
            // لو مش Owner ولا Admin يتم منعه
            echo '<button class="delete">🗑️ حذف</button>';
        }
        ?>
      </div>
    </section>

    <section class="devices">
      <h2>الأجهزة المرتبطة</h2>
      <table>
        <thead>
          <tr>
            <th>رقم الجهاز</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody id="devices-body"></tbody>
      </table>
    </section>

    <section class="sims">
      <h2>الشرائح المرتبطة</h2>
      <table>
        <thead>
          <tr>
            <th>رقم الشريحة</th>
            <th>مزود الخدمة</th>
            <th>تاريخ التفعيل</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody id="sims-body"></tbody>
      </table>
    </section>

    <section class="subscriptions">
      <h2>الاشتراكات المرتبطة</h2>
      <table>
        <thead>
          <tr>
            <th>رقم الاشتراك</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>الحالة</th>
          </tr>
        </thead>
        <tbody id="subscriptions-body"></tbody>
      </table>
    </section>

    <div class="modal hidden" id="editClientModal">
      <form id="editClientForm">
        <h2>تعديل بيانات العميل</h2>
        <input type="hidden" name="id" id="edit_id" />
        <label>الاسم:</label>
        <input type="text" name="name" id="edit_name" required />
        <label>رقم الهاتف:</label>
        <input type="text" name="contact" id="edit_contact" required />
        <label>الحالة:</label>
        <select name="status" id="edit_status">
          <option value="Active">نشط</option>
          <option value="Inactive">غير نشط</option>
        </select>
        <label>نوع العميل:</label>
        <select name="type" id="edit_type">
          <option value="Individual">فرد</option>
          <option value="Business">شركة</option>
        </select>
        <div class="modal-actions">
          <button type="submit" class="save">حفظ</button>
          <button type="button" class="cancel" onclick="closeEditModal()">إلغاء</button>
        </div>
      </form>
    </div>
  </main>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const clientId = urlParams.get("id");

    fetch(`../api/get_client_details.php?id=${clientId}`)
      .then(res => res.json())
      .then(data => {
        console.log("Fetched client data:", data);

        document.getElementById('client-name').textContent = data.client.name;
        document.getElementById('client-contact').textContent = data.client.contact_info;
        document.getElementById('client-status').textContent = data.client.account_status;

        document.getElementById('edit_id').value = data.client.customer_id;
        document.getElementById('edit_name').value = data.client.name;
        document.getElementById('edit_contact').value = data.client.contact_info;
        document.getElementById('edit_status').value = data.client.account_status;
        document.getElementById('edit_type').value = data.client.customer_type;
        
        const deviceRows = data.devices.map(device => `
          <tr>
            <td><a href="Device_Profile.php?id=${device.serial_number}">${device.serial_number}</a></td>
            <td>${device.status}</td>
          </tr>
        `).join('');
        document.getElementById('devices-body').innerHTML = deviceRows;
        
        const simRows = data.sims.map(sim => `
          <tr>
            <td><a href="sim_profile.php?id=${sim.sim_id}">${sim.line_number}</a></td>
            <td>${sim.provider}</td>
            <td>${sim.activation_date}</td>
            <td>${sim.status}</td>
          </tr>
        `).join('');
        document.getElementById('sims-body').innerHTML = simRows;

        const subRows = data.subscriptions.map(sub => `
          <tr>
            <td>${sub.subscription_id}</td>
            <td>${sub.start_date}</td>
            <td>${sub.renewal_date}</td>
            <td>${sub.status}</td>
          </tr>
        `).join('');
        document.getElementById('subscriptions-body').innerHTML = subRows;
      });

    function openEditModal() {
      document.getElementById("editClientModal").classList.remove("hidden");
    }

    function closeEditModal() {
      document.getElementById("editClientModal").classList.add("hidden");
    }

    document.getElementById("editClientForm").onsubmit = function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("../api/edit_client.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تم تعديل بيانات العميل بنجاح");
          location.reload();
        } else {
          alert("❌ فشل في تعديل البيانات");
        }
      });
    };

    document.querySelector(".delete").addEventListener("click", function () {
      if (confirm("هل أنت متأكد من حذف العميل؟")) {
        fetch(`../api/delete_client.php`, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + encodeURIComponent(clientId)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("✅ تم حذف العميل بنجاح");
            window.location.href = "Users.php";
          } else {
            alert("❌ فشل في حذف العميل");
          }
        });
      }
    });
  </script>
</body>
</html>
