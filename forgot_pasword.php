<?php
require_once 'db.php';
include 'navbar.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $identifier = trim($_POST['identifier'] ?? '');

    if (!$identifier) {
        $error = "Введите телефон или почту.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            $token = bin2hex(random_bytes(16));
            $user_id = $user['id'];

            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
            $stmt->bind_param("si", $token, $user_id);
            $stmt->execute();

            // Hozircha email yoki SMS o‘rniga brauzerga chiqaramiz:
            $success = "Ссылка для сброса пароля: <a href='reset.php?token=$token'>Нажмите сюда</a>";
        } else {
            $error = "Пользователь не найден.";
        }
        $stmt->close();
    }
}
?>