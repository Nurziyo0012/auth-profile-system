<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include 'navbar.php';
session_start();
$conn = new mysqli("localhost", "root", "", "hhproject");


// Foydalanuvchidan ma’lumot olish
$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$token = $_POST['smart-token'] ?? '';

// 🔐 SmartCaptcha tokenni tekshirish
$secret = 'ysc2_iI43pcy1IDdaVdDVjwetIZyzTU84Y4iUmEL2GVb7d74c1bd0'; // <<< bu yerga secret key’ni yozing

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "secret" => $secret,
    "token" => $token,
    "ip" => $_SERVER['REMOTE_ADDR']
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (!$result['status']) {
    die("❌ CAPTCHA tekshiruvdan o‘ta olmadi.");
}

// 👤 Email yoki telefon orqali qidirish
$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? OR phone = ?");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $name, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        header("Location: login.php");
        exit;
    } else {
        echo "❌ Noto‘g‘ri parol.";
    }
} else {
    echo "❌ Foydalanuvchi topilmadi.";
}
?>