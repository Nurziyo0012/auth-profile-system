<?php
require_once 'db.php';
include 'navbar.php';
$token = $_GET['token'] ?? '';
$success = '';
$error = '';

if (!$token) {
    header("Location: index.php");
    exit;
}

// 🔍 Tokenni tekshirish
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    $error = "Недействительный или истекший токен.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newpass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$newpass || !$confirm) {
        $error = "Введите новый пароль и подтверждение.";
    } elseif ($newpass !== $confirm) {
        $error = "Пароли не совпадают.";
    } elseif (strlen($newpass) < 6) {
        $error = "Пароль должен быть минимум 6 символов.";
    } else {
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $hash, $user['id']);
        $stmt->execute();
        $success = "✅ Пароль успешно обновлен! <a href='login.html'>Войти</a>";
    }
}
?>