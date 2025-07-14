
<?php
require_once 'db.php';

$success = '';
$errors = [];
$name = $phone = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✂️ Ma’lumotlarni olish va tozalash
    $name     = trim($_POST['name'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // ✅ Validatsiya
    if (!$name || !$phone || !$email || !$password || !$confirm) {
        $errors[] = "❗ Все поля обязательны.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "📧 Неверный формат почты.";
    }

    if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
        $errors[] = "📱 Телефон должен быть от 10 до 15 цифр.";
    }

    if ($password !== $confirm) {
        $errors[] = "🔑 Пароли не совпадают.";
    } elseif (strlen($password) < 6) {
        $errors[] = "🛡️ Пароль слишком короткий (мин. 6 символов).";
    }

    // 🔎 Tekshirish: email/phone unikalmi?
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "🚫 Такой телефон или почта уже зарегистрированы.";
    }
    $stmt->close();

    // 🗂️ Baza yozuvi
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $hash);
        if ($stmt->execute()) {
            $success = "✅ Регистрация прошла успешно! <a href='login.html'>Войти</a>";
        } else {
            $errors[] = "⚠️ Ошибка при сохранении в базу.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>📝 Регистрация</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="form-box">
  <h2>📝 Регистрация</h2>

  <?php foreach ($errors as $e): ?>
    <div class="error"><?= htmlspecialchars($e) ?></div>
  <?php endforeach; ?>

  <?php if ($success): ?>
    <div class="success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <label for="name">Имя:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>

    <label for="phone">Телефон:</label>
    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($phone) ?>" required>

    <label for="email">Почта:</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

    <label for="password">Пароль:</label>
    <input type="password" name="password" id="password" required>

    <label for="confirm">Повтор пароля:</label>
    <input type="password" name="confirm" id="confirm" required>

    <button type="submit">Зарегистрироваться</button>
  </form>
</div>

</body>
</html>