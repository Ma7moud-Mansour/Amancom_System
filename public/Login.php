<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول - Amancom</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/Login.css">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f7f8fc;
      margin: 0;
    }
    .login-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .brand {
      text-align: center;
      margin-bottom: 20px;
    }
    .brand img {
      width: 80px;
      height: auto;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
    .login-box h2 {
      margin-bottom: 20px;
      text-align: center;
    }
    .input-group {
      position: relative;
      margin-bottom: 20px;
    }
    .input-group input {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .input-group label {
      position: absolute;
      top: -10px;
      right: 10px;
      background: white;
      padding: 0 5px;
      font-size: 14px;
      color: #888;
    }
    button[type="submit"] {
      width: 100%;
      padding: 10px;
      background: #007bff;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    a {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="brand">
      <img src="../assets/logo.png" alt="Amancom Logo">
      <h1>Amancom</h1>
    </div>
    <div class="login-box">
      <h2>تسجيل الدخول</h2>
      <form id="loginForm">
        <div class="input-group">
          <input type="text" id="username" name="username" placeholder=" " required>
          <label for="username">اسم المستخدم</label>
        </div>
        
        <div class="input-group">
          <input type="password" id="password" name="password" placeholder=" " required>
          <label for="password">كلمة المرور</label>
        </div>
        
        <button type="submit">تسجيل الدخول</button>
      </form>
    </div>
  </div>

  <script>
document.getElementById("loginForm").onsubmit = function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("../api/login.php", {
    method: "POST",
    body: formData
  })
  .then(res => {
    console.log("--Raw response:", res);
    return res.json();
  })
  .then(data => {
    console.log("--Parsed response:", data);
    if (data.success) {
      alert("✅ تم تسجيل الدخول بنجاح");
      window.location.href = "Device.php";
    } else {
      alert("❌ " + (data.error || "فشل تسجيل الدخول"));
    }
  })
  .catch(err => {
    console.error("Fetch Error:", err);
    alert("حدث خطأ أثناء الاتصال بالسيرفر");
  });
};

  </script>
</body>
</html>
