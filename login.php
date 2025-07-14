<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">

    <div class="form-box">
  <h2>🔐 Вход</h2>

<?php if (!empty ($error)): ?>
  <div class="error">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

  <form method="POST" action="login.php">
    <input type="text" name="identifier" placeholder="Телефон или Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Войти</button>
  </form>
</div>

</head>
<body>
<?php
if(session_status() == PHP_SESSION_NONE){
session_start();
}
require_once 'db.php';
include 'navbar.php';

$identifier = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (!$identifier || !$password) {
        $error = "Введите телефон/почту и пароль.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, phone, email, password FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: profile.php");
                exit;
            } else {
                $error = "❌ Неверный пароль.";
            }
        } else {
            $error = "❌ Пользователь не найден.";
        }
        $stmt->close();
    }
}
?>