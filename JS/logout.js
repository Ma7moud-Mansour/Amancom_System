function logout() {
    fetch("../api/logout.php", { method: "POST" })
      .then(res => res.json())
      .then(data => {
        // مسح بيانات المستخدم من localStorage
        localStorage.removeItem("user");
        // تحويل المستخدم لصفحة تسجيل الدخول
        window.location.href = "Login.html";
      })
      .catch(err => {
        alert("❌ حدث خطأ أثناء تسجيل الخروج");
        console.error(err);
      });
  }