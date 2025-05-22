document.addEventListener("DOMContentLoaded", () => {
    const userModal = document.getElementById("userModal");
    const form = document.getElementById("userForm");
    const addBtn = document.getElementById("addUserBtn");
    const cancelBtn = document.getElementById("cancelUserModal");
    const userTableBody = document.getElementById("user-body");
    const formTitle = document.getElementById("formTitle");
  
    let currentUserRole = "";
  
    // Load current user role (assume API returns it)
    fetch("../api/get_current_user_role.php")
      .then(res => res.json())
      .then(data => {
        currentUserRole = data.role;
        if (currentUserRole !== "Owner" && currentUserRole !== "Admin") {
          document.querySelector("main").innerHTML = "<h2>🚫 لا تملك صلاحية الوصول لهذه الصفحة</h2>";
        } else {
          loadUsers();
        }
      });
  
    function loadUsers() {
      fetch("../api/get_all_users.php")
        .then(res => res.json())
        .then(users => {
          userTableBody.innerHTML = users.map(user => `
            <tr data-id="${user.user_id}">
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>${user.role}</td>
              <td class="actions">
                ${canEdit(user.role) ? `<button class="edit">✏️</button>` : ""}
                ${canDelete(user.role) ? `<button class="delete">🗑️</button>` : ""}
              </td>
            </tr>
          `).join("");
        });
    }
  
    function canEdit(role) {
      if (currentUserRole === "Owner") return true;
      if (currentUserRole === "Admin" && role === "Normal") return true;
      return false;
    }
  
    function canDelete(role) {
      return canEdit(role); // same logic
    }
  
    addBtn.onclick = () => {
      form.reset();
      formTitle.textContent = "إضافة مستخدم";
      document.getElementById("user_id").value = "";
      userModal.classList.remove("hidden");
    };
  
    cancelBtn.onclick = () => userModal.classList.add("hidden");
  
    document.addEventListener("click", e => {
      const row = e.target.closest("tr");
      const id = row?.dataset.id;
  
      if (e.target.classList.contains("edit")) {
        fetch(`../api/get_user.php?id=${id}`)
          .then(res => res.json())
          .then(user => {
            document.getElementById("user_id").value = user.user_id;
            document.getElementById("name").value = user.name;
            document.getElementById("email").value = user.email;
            document.getElementById("password").value = ""; // don’t show
            document.getElementById("role").value = user.role;
            formTitle.textContent = "تعديل مستخدم";
            userModal.classList.remove("hidden");
          });
      }
  
      if (e.target.classList.contains("delete")) {
        if (confirm("هل أنت متأكد من حذف المستخدم؟")) {
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
  
    form.onsubmit = e => {
      e.preventDefault();
      const formData = new FormData(form);
      const isEdit = !!formData.get("user_id");
  
      if (currentUserRole === "Admin" && formData.get("role") !== "Normal") {
        alert("⚠️ لا يمكنك تعيين صلاحية غير موظف");
        return;
      }
  
      fetch(`../api/${isEdit ? "edit_user.php" : "add_user.php"}`, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ تمت العملية بنجاح");
          userModal.classList.add("hidden");
          loadUsers();
        } else {
          alert("❌ فشل في العملية");
        }
      });
    };
  
    document.getElementById("searchInput").addEventListener("input", function () {
      const keyword = this.value.toLowerCase();
      const rows = document.querySelectorAll("#user-body tr");
      rows.forEach(row => {
        const name = row.children[0].textContent.toLowerCase();
        const email = row.children[1].textContent.toLowerCase();
        row.style.display = name.includes(keyword) || email.includes(keyword) ? "" : "none";
      });
    });
  });
  