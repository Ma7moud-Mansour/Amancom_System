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
  <title>تفاصيل الشريحة - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Cairo', sans-serif; background: #f7f8fc; margin: 0; padding: 0 20px; }
    .profile-details {
      background: white; padding: 20px; margin-top: 40px;
      border-radius: 14px; box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      max-width: 700px; margin-left: auto; margin-right: auto;
    }
    .profile-details h2, .client-info h3 { margin-bottom: 15px; }
    .profile-details p, .client-info p { margin: 5px 0; color: #555; }
    .client-info { margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 10px; }
    .actions { margin-top: 20px; text-align: center; }
    .actions button {
      margin: 5px; padding: 10px 15px; border: none; border-radius: 6px;
      cursor: pointer; font-size: 16px; color: white;
    }
    .link-btn { background: #28a745; }
    .unlink-btn { background: #dc3545; }

    .modal.hidden { display: none !important; }
    .modal {
      position: fixed; top: 0; right: 0; bottom: 0; left: 0;
      background-color: rgba(0, 0, 0, 0.5); display: flex;
      justify-content: center; align-items: center; z-index: 1000;
    }
    .modal form {
      background: white; padding: 30px; border-radius: 10px;
      width: 100%; max-width: 400px;
    }
    #clientResults { list-style: none; padding: 0; margin: 10px 0 0 0; max-height: 150px; overflow-y: auto; border: 1px solid #ccc; }
    #clientResults li {
      padding: 8px 10px; background: #f9f9f9; border-bottom: 1px solid #ddd;
      cursor: pointer;
    }
    #clientResults li:hover {
      background: #e9ecef;
    }
  </style>
</head>
<body>

  <section class="profile-details">
    <h2>بيانات الشريحة</h2>
    <p><strong>رقم الشريحة:</strong> <span id="line_number">جار التحميل...</span></p>
    <p><strong>مزود الخدمة:</strong> <span id="provider">جار التحميل...</span></p>
    <p><strong>نوع الخط:</strong> <span id="line_type">جار التحميل...</span></p>
    <p><strong>الحالة:</strong> <span id="status">جار التحميل...</span></p>
    <p><strong>تاريخ البيع:</strong> <span id="sell_date">جار التحميل...</span></p>
    <p><strong>تاريخ التفعيل:</strong> <span id="activation_date">جار التحميل...</span></p>
    <p><strong>تاريخ الانتهاء:</strong> <span id="expiration_date">جار التحميل...</span></p>

    <div class="client-info" id="client-info" style="display: none;">
      <h3>العميل المرتبط</h3>
      <p><strong>الاسم:</strong> <span id="customer_name">جار التحميل...</span></p>
      <p><strong>رقم الهاتف:</strong> <span id="customer_contact">جار التحميل...</span></p>
    </div>

    <div class="actions" id="actions-area"></div>
  </section>

  <div class="modal hidden" id="linkModal">
    <form id="linkForm" onsubmit="return false;">
      <h3>ربط الشريحة بعميل</h3>
      <input type="text" id="clientSearch" placeholder="ابحث عن عميل بالاسم أو الرقم..." />
      <ul id="clientResults"></ul>
      <div style="margin-top: 10px;">
        <button type="button" onclick="closeLinkModal()" class="unlink-btn">إلغاء</button>
      </div>
    </form>
  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const simId = urlParams.get("id");

    fetch(`../api/get_sim_details.php?id=${simId}`)
      .then(res => res.json())
      .then(data => {
        if (data.error) return alert(data.error);
        const sim = data.sim;
        document.getElementById('line_number').textContent = sim.line_number;
        document.getElementById('provider').textContent = sim.provider;
        document.getElementById('line_type').textContent = sim.line_type;
        document.getElementById('status').textContent = sim.status;
        document.getElementById('sell_date').textContent = sim.sell_date;
        document.getElementById('activation_date').textContent = sim.activation_date;
        document.getElementById('expiration_date').textContent = sim.expiration_date;

        const actionsArea = document.getElementById("actions-area");

        if (data.customer) {
          document.getElementById("client-info").style.display = "block";
          document.getElementById("customer_name").textContent = data.customer.name;
          document.getElementById("customer_contact").textContent = data.customer.contact_info;
          actionsArea.innerHTML = `<button class="unlink-btn" onclick="unlinkSim()">❌ إلغاء الربط</button>`;
        } else {
          actionsArea.innerHTML = `<button class="link-btn" onclick="openLinkModal()">🔗 ربط بعميل</button>`;
        }
      });

    function openLinkModal() {
      document.getElementById("linkModal").classList.remove("hidden");
    }

    function closeLinkModal() {
      document.getElementById("linkModal").classList.add("hidden");
    }

    document.getElementById("clientSearch").addEventListener("input", function () {
      const keyword = this.value.trim();
      const resultBox = document.getElementById("clientResults");

      if (keyword.length < 2) return resultBox.innerHTML = "";

      fetch(`../api/search_clients.php?query=${encodeURIComponent(keyword)}`)
        .then(res => res.json())
        .then(results => {
          resultBox.innerHTML = results.map(c => `
            <li onclick="bindSimToClient('${c.customer_id}')">${c.name} - ${c.contact_info}</li>
          `).join("");
        });
    });

    function bindSimToClient(customerId) {
      fetch("../api/link_sim_to_customer.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "sim_id=" + encodeURIComponent(simId) + "&customer_id=" + encodeURIComponent(customerId)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تم الربط بالعميل بنجاح");
          location.reload();
        } else {
          alert("❌ فشل في الربط: " + (data.error || ""));
        }
      });
    }

    function unlinkSim() {
      if (!confirm("هل أنت متأكد من إلغاء ربط الشريحة بالعميل؟")) return;
      fetch("../api/unlink_sim.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "sim_id=" + encodeURIComponent(simId)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تم إلغاء الربط");
          location.reload();
        } else {
          alert("❌ فشل في إلغاء الربط");
        }
      });
    }
  </script>

</body>
</html>
