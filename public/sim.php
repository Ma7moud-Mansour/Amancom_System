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
  <title>إدارة الشرائح - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../Style/Dashboard.css" />
  <link rel="stylesheet" href="../Style/Sim.css" />
  <style>
    .modal.hidden { display: none !important; }
    .modal {
      position: fixed;
      top: 0; right: 0; bottom: 0; left: 0;
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
      <h1>إدارة الشرائح</h1>
      <div class="user-info">مرحباً، <strong>محمود عبدالكريم</strong> 👋</div>
    </div>

    <div class="top-bar">
      <input type="text" id="searchInput" placeholder="ابحث برقم الشريحة أو المزود..." />
      <button id="addSimBtn">➕ إضافة شريحة</button>
    </div>

    <section class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>رقم الشريحة</th>
            <th>المزود</th>
            <th>تاريخ الانتهاء</th>
            <th>الحالة</th>
            <th>الخيارات</th>
          </tr>
        </thead>
        <tbody id="sim-body"></tbody>
      </table>
    </section>

    <div class="modal hidden" id="simModal">
      <form id="simForm">
        <h2 id="formTitle">إضافة شريحة</h2>
        <input type="hidden" name="id" id="sim_id" />
        <label>رقم الشريحة:</label>
        <input type="text" name="line_number" id="line_number" required />
        <label>مزود الخدمة:</label>
        <select name="provider" id="provider" required>
          <option>Vodafone</option>
          <option>Orange</option>
          <option>Etisalat</option>
          <option>WE</option>
        </select>
        <label>تاريخ الانتهاء:</label>
        <input type="date" name="activation_date" id="activation_date" required />
        <label>الحالة:</label>
        <select name="status" id="status">
          <option value="Active">نشطة</option>
          <option value="Inactive">غير مفعّلة</option>
        </select>
        <div class="modal-actions">
          <button type="submit" class="save">حفظ</button>
          <button type="button" id="cancelSimModal" class="cancel">إلغاء</button>
        </div>
      </form>
    </div>
  </main>

  <script>
    const modal = document.getElementById("simModal");
    const form = document.getElementById("simForm");
    const addBtn = document.getElementById("addSimBtn");
    const cancelBtn = document.getElementById("cancelSimModal");
    const formTitle = document.getElementById("formTitle");

    function openModal(edit = false, data = {}) {
      modal.classList.remove("hidden");
      if (edit) {
        formTitle.textContent = "تعديل شريحة";
        document.getElementById("sim_id").value = data.sim_id;
        document.getElementById("line_number").value = data.line_number;
        document.getElementById("provider").value = data.provider;
        document.getElementById("activation_date").value = data.activation_date;
        document.getElementById("status").value = data.status;
      } else {
        form.reset();
        document.getElementById("sim_id").value = "";
        formTitle.textContent = "إضافة شريحة";
      }
    }

    function closeModal() {
      modal.classList.add("hidden");
    }

    function loadSims() {
      fetch("../api/get_all_sims.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("sim-body");
          tbody.innerHTML = data.map(sim => `
            <tr data-id="${sim.sim_id}">
              <td class="line_number"><a href="Sim_Profile.php?id=${sim.sim_id}">${sim.line_number}</a></td>
              <td class="provider">${sim.provider}</td>
              <td class="activation_date">${sim.activation_date}</td>
              <td class="status">${sim.status}</td>
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
      const isEdit = !!formData.get("id");
      fetch(`../api/${isEdit ? "edit_sim.php" : "add_sim.php"}`, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تمت العملية بنجاح");
          closeModal();
          loadSims();
        } else {
          alert("❌ فشل في العملية");
        }
      });
    };

    document.addEventListener("click", function (e) {
      if (e.target.classList.contains("edit")) {
        const row = e.target.closest("tr");
        openModal(true, {
          sim_id: row.dataset.id,
          line_number: row.querySelector(".line_number").textContent,
          provider: row.querySelector(".provider").textContent,
          activation_date: row.querySelector(".activation_date").textContent,
          status: row.querySelector(".status").textContent
        });
      }

      if (e.target.classList.contains("delete")) {
        const id = e.target.closest("tr").dataset.id;
        if (confirm("هل أنت متأكد من الحذف؟")) {
          fetch("../api/delete_sim.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + encodeURIComponent(id)
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              console.log(id);
              alert("✅ تم الحذف بنجاح");
              loadSims();
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
      const rows = document.querySelectorAll("#sim-body tr");
      rows.forEach(row => {
        const line = row.querySelector(".line_number").textContent.toLowerCase();
        const provider = row.querySelector(".provider").textContent.toLowerCase();
        row.style.display = (line.includes(keyword) || provider.includes(keyword)) ? "" : "none";
      });
    });

    loadSims();
  </script>
</body>
</html>
