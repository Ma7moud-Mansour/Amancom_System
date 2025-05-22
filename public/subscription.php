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
  <title>إدارة الاشتراكات - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../Style/Dashboard.css" />
  <link rel="stylesheet" href="../Style/Subscription.css" />
  <style>
    .modal.hidden { display: none !important; }
    .modal {
      position: fixed; top: 0; right: 0; bottom: 0; left: 0;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex; justify-content: center; align-items: center;
      z-index: 1000;
    }
    .modal form {
      background: white; padding: 30px; border-radius: 10px;
      width: 100%; max-width: 500px;
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
    <h1>إدارة الاشتراكات</h1>
    <div class="user-info">مرحباً، <strong><?php echo $_SESSION['user']['username']; ?></strong> 👋</div>
  </div>

  <div class="top-bar">
    <input type="text" id="searchInput" placeholder="ابحث برقم الاشتراك أو العميل..." />
    <button id="addSubscriptionBtn">➕ إضافة اشتراك</button>
  </div>

  <section class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>رقم الاشتراك</th>
          <th>السيرفر</th>
          <th>العميل</th>
          <th>المبلغ</th>
          <th>المدة</th>
          <th>البداية</th>
          <th>النهاية</th>
          <th>الحالة</th>
          <th>الخيارات</th>
        </tr>
      </thead>
      <tbody id="subscription-body"></tbody>
    </table>
  </section>

  <div class="modal hidden" id="subscriptionModal">
    <form id="subscriptionForm">
      <h2 id="formTitle">إضافة اشتراك</h2>
      <input type="hidden" name="subscription_id" id="subscription_id" />

      <label>اسم السيرفر:</label>
      <input type="text" name="server_name" id="server_name" required />

      <label>رقم العميل:</label>
      <input type="text" name="customer_id" id="customer_id" required />

      <label>المبلغ:</label>
      <input type="number" name="amount" id="amount" required />

      <label>المدة:</label>
      <select name="duration" id="duration">
        <option value="Monthly">شهري</option>
        <option value="Yearly">سنوي</option>
      </select>

      <label>تاريخ البداية:</label>
      <input type="date" name="start_date" id="start_date" required />

      <label>تاريخ النهاية:</label>
      <input type="date" name="renewal_date" id="renewal_date" required />

      <label>الحالة:</label>
      <select name="status" id="status">
        <option value="Active">فعال</option>
        <option value="Pending">في الانتظار</option>
        <option value="Expired">منتهي</option>
      </select>

      <div class="modal-actions">
        <button type="submit" class="save" >حفظ</button>
        <button type="button" id="cancelSubscriptionModal" class="cancel">إلغاء</button>
      </div>
    </form>
  </div>
</main>

<script>
  
  const modal = document.getElementById("subscriptionModal");
  const form = document.getElementById("subscriptionForm");
const cancelBtn = document.getElementById("cancelSubscriptionModal");
const addBtn = document.getElementById("addSubscriptionBtn");
const formTitle = document.getElementById("formTitle");

function closeModal() {
  modal.classList.add("hidden");
}

function openModal(edit = false, data = {}) {
  modal.classList.remove("hidden");
  formTitle.textContent = edit ? "تعديل اشتراك" : "إضافة اشتراك";
  form.dataset.editing = edit;

  form.reset();
  document.getElementById("subscription_id").value = data.subscription_id || "";
  document.getElementById("server_name").value = data.server_name || "";
  document.getElementById("customer_id").value = data.customer_id || "";
  document.getElementById("amount").value = data.amount || "";
  document.getElementById("duration").value = data.duration || "Monthly";
  document.getElementById("start_date").value = data.start_date || "";
  document.getElementById("renewal_date").value = data.renewal_date || "";
  document.getElementById("renewal_date").value = data.end_date || "";
}

form.onsubmit = function (e) {
  e.preventDefault();
  const isEdit = form.dataset.editing === "true";
  const formData = new FormData(form);
  if (isEdit) {
    formData.append("subscription_id", document.getElementById("subscription_id").value);
  }
  fetch(`../api/${isEdit ? "edit_subscription.php" : "add_subscription.php"}`, {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("✅ تمت العملية بنجاح");
      closeModal();
      loadSubscriptions();
    } else {
      alert("❌ فشل في العملية");
    }
  });
};

cancelBtn.onclick = () => closeModal();
addBtn.onclick = () => openModal();

function loadSubscriptions() {
  fetch("../api/get_all_subscriptions.php")
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("subscription-body");
      tbody.innerHTML = data.map(sub => `
      <tr data-id="${sub.subscription_id}">
          <td class="subscription_id">${sub.subscription_id}</td>
          <td class="server_name">${sub.server_name}</td>
          <td class="customer_name">${sub.customer_name}</td>
          <td class="amount">${sub.amount}</td>
          <td class="duration">${sub.duration}</td>
          <td class="start_date">${sub.start_date}</td>
          <td class="end_date">${sub.renewal_date}</td>
          <td class="status">${sub.status}</td>
          <td>
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
      subscription_id: row.dataset.id,
      server_name: row.querySelector(".server_name").textContent,
      customer_id: row.querySelector(".customer_id").textContent,
      amount: row.querySelector(".amount").textContent,
      duration: row.querySelector(".duration").textContent,
      start_date: row.querySelector(".start_date").textContent,
      end_date: row.querySelector(".end_date").textContent,
      status: row.querySelector(".status").textContent
    });
  }

  if (e.target.classList.contains("delete")) {
    const id = e.target.closest("tr").dataset.id;
    if (confirm("هل أنت متأكد من الحذف؟")) {
      fetch("../api/delete_subscription.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + encodeURIComponent(id)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تم الحذف بنجاح");
          loadSubscriptions();
        } else {
          alert("❌ فشل في الحذف");
        }
      });
    }
  }
});

// تحميل البيانات عند فتح الصفحة
loadSubscriptions();
form.onsubmit = function (e) {
  e.preventDefault();
  const isEdit = form.dataset.editing === "true";
  const formData = new FormData(form);
  if (isEdit) {
    formData.append("subscription_id", document.getElementById("subscription_id").value);
  }
  fetch(`../api/${isEdit ? "edit_subscription.php" : "add_subscription.php"}`, {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("✅ تمت العملية بنجاح");
      closeModal();
      loadSubscriptions();
    } else {
      alert("❌ فشل في العملية");
      console.error("Error:", data.error);
    }
  });
};


document.addEventListener("click", function (e) {
if (e.target.classList.contains("edit")) {
  const row = e.target.closest("tr");
  openModal(true, {
    serial_number: row.dataset.id,
    subscription_type: row.querySelector(".subscription_type").textContent,
    status: row.querySelector(".status").textContent,
    purchase_date: row.querySelector(".purchase_date")?.textContent || ''
  });
}});
</script>
</body>
</html>
