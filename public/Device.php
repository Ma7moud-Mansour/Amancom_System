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
  <title>إدارة الأجهزة - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../Style/Dashboard.css" />
  <link rel="stylesheet" href="../Style/Device.css" />
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
      <h1>إدارة الأجهزة</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <div class="top-bar">
      <input type="text" id="searchInput" placeholder="ابحث برقم الجهاز أو الحالة..." />
      <button id="addDeviceBtn">➕ إضافة جهاز</button>
    </div>

    <section class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>رقم الجهاز</th>
            <th>النوع</th>
            <th>الحالة</th>
            <th>العميل المرتبط</th>
            <th>الخيارات</th>
          </tr>
        </thead>
        <tbody id="device-body"></tbody>
      </table>
    </section>

    <div class="modal hidden" id="deviceModal">
      <form id="deviceForm">
        <h2 id="formTitle">إضافة جهاز</h2>
        <input type="hidden" name="original_serial" id="original_serial" />
        <label>رقم الجهاز:</label>
        <input type="text" name="serial_number" id="serial_number" required />
        <label>النوع:</label>
        <input type="text" name="device_type" id="device_type" required />
        <label>الحالة:</label>
        <select name="status" id="status">
          <option value="Available">متاح</option>
          <option value="Assigned">مستخدم</option>
          <option value="Damaged">تالف</option>
        </select>
        <label>تاريخ الشراء:</label>
        <input type="date" name="purchase_date" id="purchase_date" />
        <div class="modal-actions">
          <button type="submit" class="save">حفظ</button>
          <button type="button" id="cancelDeviceModal" class="cancel">إلغاء</button>
        </div>
      </form>
    </div>
  </main>

  <script>

  
    const modal = document.getElementById("deviceModal");
    const form = document.getElementById("deviceForm");
    const addBtn = document.getElementById("addDeviceBtn");
    const cancelBtn = document.getElementById("cancelDeviceModal");
    const formTitle = document.getElementById("formTitle");

    function openModal(edit = false, data = {}) {
      modal.classList.remove("hidden");
      if (edit) {
        formTitle.textContent = "تعديل جهاز";
        document.getElementById("original_serial").value = data.serial_number;
        document.getElementById("serial_number").value = data.serial_number;
        document.getElementById("device_type").value = data.device_type;
        document.getElementById("status").value = data.status;
        document.getElementById("purchase_date").value = data.purchase_date;
      } else {
        form.reset();
        formTitle.textContent = "إضافة جهاز";
        document.getElementById("original_serial").value = "";
      }
    }

    function closeModal() {
      modal.classList.add("hidden");
    }

    function loadDevices() {
      fetch("../api/get_all_devices.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("device-body");
          tbody.innerHTML = data.map(device => `
            <tr data-id="${device.serial_number}">
              <td class="serial_number"><a href="Device_Profile.php?id=${device.serial_number}">${device.serial_number}</a></td>
              <td class="device_type">${device.device_type}</td>
              <td class="status">${device.status}</td>
              <td class="customer">${device.customer_name || '---'}</td>
              <td>
                <button class="edit">✏️</button>
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
          `).join("");
        });
    }

    form.onsubmit = function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      const isEdit = !!formData.get("original_serial");

      fetch(`../api/${isEdit ? "edit_device.php" : "add_device.php"}`, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تمت العملية بنجاح");
          closeModal();
          loadDevices();
        } else {
          alert("❌ فشل في العملية");
        }
      });
    };

    document.addEventListener("click", function (e) {
      if (e.target.classList.contains("edit")) {
        const row = e.target.closest("tr");
        openModal(true, {
          serial_number: row.dataset.id,
          device_type: row.querySelector(".device_type").textContent,
          status: row.querySelector(".status").textContent,
          purchase_date: row.querySelector(".purchase_date")?.textContent || ''
        });
      }

      if (e.target.classList.contains("delete")) {
        const serial = e.target.closest("tr").dataset.id;
        if (confirm("هل أنت متأكد من الحذف؟")) {
          fetch("../api/delete_device.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "serial_number=" + encodeURIComponent(serial)
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert("✅ تم الحذف بنجاح");
              loadDevices();
            } else {
              alert("❌ فشل في الحذف");
            }
          });
        }
      }
    });

    addBtn.onclick = () => openModal();
    cancelBtn.onclick = () => closeModal();

    document.getElementById("searchInput").addEventListener("input", function () {
      const keyword = this.value.toLowerCase();
      const rows = document.querySelectorAll("#device-body tr");
      rows.forEach(row => {
        const serial = row.querySelector(".serial_number").textContent.toLowerCase();
        const status = row.querySelector(".status").textContent.toLowerCase();
        row.style.display = (serial.includes(keyword) || status.includes(keyword)) ? "" : "none";
      });
    });

    loadDevices();
  </script>
</body>
</html>
