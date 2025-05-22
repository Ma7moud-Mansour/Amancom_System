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
  <title>إدارة المستخدمين - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/Dashboard.css">
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f7f8fc; margin: 0; padding: 0 20px; }
    .main-header { margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
    .main-header h1 { margin: 0; }
    .top-bar { margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
    .top-bar input { padding: 10px; width: 250px; border-radius: 6px; border: 1px solid #ccc; }
    .top-bar button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: right; }
    th { background-color: #f2f2f2; font-weight: bold; }
    .actions button { margin-left: 5px; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
    .edit { background-color: #ffc107; color: white; }
    .delete { background-color: #dc3545; color: white; }
    .modal.hidden { display: none !important; }
    .modal { position: fixed; top: 0; right: 0; bottom: 0; left: 0; background-color: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; }
    .modal form { background: white; padding: 30px; border-radius: 10px; width: 100%; max-width: 500px; box-shadow: 0 0 20px rgba(0,0,0,0.2); }
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
      <h1>إدارة المستخدمين</h1>
      <div class="user-info" id="user-info">جار التحميل...</div>
    </div>

    <div class="top-bar">
      <input type="text" id="searchInput" placeholder="ابحث عن مستخدم...">
      <button id="addUserBtn">➕ إضافة مستخدم</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>اسم المستخدم</th>
          <th>كلمة السر</th>
          <th>الدور</th>
        </tr>
      </thead>
      <tbody id="user-body"></tbody>
    </table>

    <div class="modal hidden" id="userModal">
      <form id="userForm">
        <h2 id="formTitle">إضافة مستخدم</h2>
        <input type="hidden" name="user_id" id="user_id">
        <label>الاسم:</label>
        <input type="text" name="username" id="username" required>
        <label>كلمة المرور:</label>
        <input type="password" name="password" id="password" required>
        <label>الدور:</label>
        <select name="role" id="role">
          <option value="Normal">موظف</option>
          <option value="Admin">مشرف</option>
          <option value="Owner">مالك</option>
        </select>
        <div class="modal-actions">
          <button type="submit" class="save">حفظ</button>
          <button type="button" id="cancelUserModal" class="cancel">إلغاء</button>
        </div>
      </form>
    </div>
  </main>

  <!-- ✅ كود التحقق من الجلسة -->
  <script>
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("userModal");
  const form = document.getElementById("userForm");
  const addBtn = document.getElementById("addUserBtn");
  const cancelBtn = document.getElementById("cancelUserModal");
  const formTitle = document.getElementById("formTitle");

  function closeModal() {
    modal.classList.add("hidden");
  }

  function openModal(edit = false, data = {}) {
    modal.classList.remove("hidden");
    formTitle.textContent = edit ? "تعديل مستخدم" : "إضافة مستخدم";
    form.dataset.editing = edit;
    form.reset();
    document.getElementById("user_id").value = data.user_id || "";
    document.getElementById("username").value = data.username || "";
    document.getElementById("password").value = data.password || "";
    document.getElementById("password").value = "";
    document.getElementById("role").value = data.role || "Normal";
  }

  form.onsubmit = function (e) {
    e.preventDefault();
    const isEdit = form.dataset.editing === "true";
    const formData = new FormData(form);
    const url = isEdit ? "../api/edit_user.php" : "../api/add_user.php";
    fetch(url, {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تمت العملية بنجاح");
          closeModal();
          loadUsers();
        } else {
          alert("❌ فشل في العملية");
        }
      });
  };

  cancelBtn.onclick = () => closeModal();
  addBtn.onclick = () => openModal();

  function loadUsers() {
    fetch("../api/get_all_users.php")
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById("user-body");
        tbody.innerHTML = data.map(user => `
          <tr data-id="${user.user_id}">
            <td class="username">${user.username}</td>
            <td class="password">${user.password}</td>
            <td class="role">${user.role}</td>
            <td class="actions">
              <button class="edit">✏️</button>
              <button class="delete">🗑️</button>
            </td>
          </tr>
        `).join("");
      });
  }

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit")) {
      const row = e.target.closest("tr");
      openModal(true, {
        user_id: row.dataset.id,
        username: row.querySelector(".username").textContent,
        password: row.querySelector(".password").textContent,
        role: row.querySelector(".role").textContent
      });
    }

    if (e.target.classList.contains("delete")) {
      const id = e.target.closest("tr").dataset.id;
      if (confirm("هل أنت متأكد من الحذف؟")) {
        fetch("../api/delete_user.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + encodeURIComponent(id)
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert("✅ تم الحذف بنجاح");
              loadUsers();
            } else {
              alert("❌ فشل في الحذف");
            }
          });
      }
    }
  });

  loadUsers();
});    

  </script>

</body>
</html>
