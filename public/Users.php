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
  <title>إدارة المستخدمين - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/Dashboard.css">
  <link rel="stylesheet" href="../Style/Users.css">
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
      <h1>إدارة المستخدمين</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <div class="top-bar">
      <input type="text" id="searchInput" placeholder="ابحث باسم العميل..." oninput="filterClients()" />
      <button id="addUserBtn">➕ إضافة مستخدم</button>
    </div>

    <section class="table-wrapper">
      <table>
        <thead>
          <tr data-id="${user.customer_id}">
            <th>الاسم</th>
            <th>البريد الإلكتروني</th>
            <th>النوع</th>
            <th>تاريخ الإنضمام</th>
            <th>الخيارات</th>
          </tr>
        </thead>
        <tbody id="users-body"></tbody>
      </table>
    </section>

<!-- Modal لإضافة عميل جديد -->
<div class="modal hidden" id="addClientModal">
  <form id="addClientForm">
    <h2>إضافة عميل جديد</h2>
    <label>الاسم:</label>
    <input type="text" name="name" required />

    <label>نوع العميل:</label>
    <select name="type">
      <option value="Individual">فرد</option>
      <option value="Business">شركة</option>
    </select>

    <label>رقم الهاتف:</label>
    <input type="text" name="contact" required />

    <label>الحالة:</label>
    <select name="status">
      <option value="Active">نشط</option>
      <option value="Inactive">غير نشط</option>
    </select>

    <div class="modal-actions">
      <button type="submit" class="save">حفظ</button>
      <button type="button" class="cancel" onclick="closeModal()">إلغاء</button>
    </div>
  </form>
</div>


    <div class="modal hidden" id="userModal">
      <form>
        <h2>إضافة مستخدم جديد</h2>
        <label>الاسم:</label>
        <input type="text" required />

        <label>البريد الإلكتروني:</label>
        <input type="email" required />

        <label>كلمة المرور:</label>
        <input type="password" required />

        <label>النوع:</label>
        <select>
          <option>Admin</option>
          <option>User</option>
        </select>

        <div class="modal-actions">
          <button type="submit" class="save">حفظ</button>
          <button type="button" id="cancelUserModal" class="cancel">إلغاء</button>
        </div>
      </form>
    </div>
  </main>

  <script>
  fetch('../api/get_all_clients.php')
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
        return;
      }
      const tableBody = document.getElementById('users-body');
      tableBody.innerHTML = data.map(user => `
        <tr data-id="${user.customer_id}">
          <td class="c-name"><a href="Client_Profile.php?id=${user.customer_id}">${user.name}</a></td>
          <td class="c-contact">${user.contact_info}</td>
          <td class="c-type">${user.customer_type}</td>
          <td class="c-status">${user.account_status}</td>
          <td>
              <?php
              // تحقق من صلاحيات الدور
              $role = $_SESSION['user']['role'];
              if ($role == 'Owner' || $role == 'Admin') {
                  // لو مش Owner ولا Admin يتم منعه
                  echo '<button class="delete">🗑️</button>';
              }
            ?>
          </td>
        </tr>
      `).join('');
    });

  document.getElementById("addUserBtn").onclick = () => {
    document.getElementById("addClientModal").classList.remove("hidden");
  };

  function closeModal() {
    document.getElementById("addClientModal").classList.add("hidden");
  }

  document.getElementById("addClientForm").onsubmit = function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("../api/add_client.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("✅ تم إضافة العميل بنجاح");
        location.reload();
      } else {
        alert("❌ فشل في إضافة العميل");
      }
    });
  };

  // حذف عميل
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("delete")) {
      const row = e.target.closest("tr");
      const id = row.dataset.id;
      if (confirm("هل أنت متأكد من حذف العميل؟")) {
        fetch("../api/delete_client.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("✅ تم الحذف بنجاح");
            row.remove();
          } else {
            alert("❌ فشل في الحذف");
          }
        });
      }
    }
  });

  // تعديل عميل (فتح المودال)
  document.addEventListener("click", function (e) {
  if (e.target.classList.contains("edit")) {
    const row = e.target.closest("tr");
    if (!row) return;
    
    const id = row.dataset.id;
    const name = row.querySelector(".c-name")?.textContent?.trim() ?? "";
    const type = row.querySelector(".c-type")?.textContent?.trim() ?? "";
    const contact = row.querySelector(".c-contact")?.textContent?.trim() ?? "";
    const status = row.querySelector(".c-status")?.textContent?.trim() ?? "";
    
    document.getElementById("edit_id").value = id;
    console.log("Omaar")  
    document.getElementById("edit_name").value = name;
    document.getElementById("edit_type").value = type;
    document.getElementById("edit_contact").value = contact;
    document.getElementById("edit_status").value = status;
    
    document.getElementById("editClientModal").classList.remove("hidden");
  }
});


  // إرسال التعديل
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
        alert("✅ تم التعديل بنجاح");
        location.reload();
      } else {
        alert("❌ فشل في التعديل");
      }
    });
  };

  // إغلاق مودال التعديل
  function closeEditModal() {
    document.getElementById("editClientModal").classList.add("hidden");
  }

  // بحث
  function filterClients() {
    const keyword = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#users-body tr");
    rows.forEach(row => {
      const name = row.querySelector(".c-name").textContent.toLowerCase();
      const type = row.querySelector(".c-type").textContent.toLowerCase();
      row.style.display = (name.includes(keyword) || type.includes(keyword)) ? "" : "none";
    });
  }
</script>


</body>
</html>
