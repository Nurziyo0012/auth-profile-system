<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Главная</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f7f5ff;
      font-family: 'Segoe UI', sans-serif;
    }

    nav {
      background: #6c63ff;
      padding: 12px 20px;
      color: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .navbar-container {
      max-width: 960px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .navbar-left a, .navbar-right a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: bold;
    }

    /* 📦 Welcome block yuqorida */
    .entry-box {
      max-width: 600px;
      margin: 40px auto 0;
      padding: 30px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      text-align: center;
    }
    .entry-box h2 {
      margin-bottom: 20px;
      color: #6c63ff;
    }
    .entry-box p {
      font-size: 16px;
      color: #444;
      margin-bottom: 30px;
    }

    .entry-buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }
    .entry-buttons button {
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 6px;
      border: none;
      background-color: #6c63ff;
      color: white;
      cursor: pointer;
    }
    .entry-buttons button:hover {
      background-color: #504ac9;
    }
  </style>
</head>
<body>

<!-- 🔝 Tepa navbar -->
<nav>
  <div class="navbar-container">
    <div class="navbar-left">
      <a href="index.php">🏠 Главная</a>
    </div>
    <div class="navbar-right">
      <a href="login.php">Вход</a>
      <a href="register.php">Регистрация</a>
    </div>
  </div>
</nav>

<!-- 🔝 Entry blok — sahifaning yuqori qismida -->
<div class="entry-box">
  <h2>Добро пожаловать!</h2>
  <p>Это система авторизации. Войдите или зарегистрируйтесь, чтобы начать работу.</p>

  <div class="entry-buttons">
    <a href="login.php"><button>Вход</button></a>
    <a href="register.php"><button>Регистрация</button></a>
  </div>
</div>

</body>
</html>