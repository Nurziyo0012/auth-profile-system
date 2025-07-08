<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Ro‘yxatdan o‘tish</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-box">
        <h2>Ro‘yxatdan o‘tish</h2>
<?php
include 'navbar.php';
session_start();
$conn = new mysqli("localhost", "root", "", "hhproject");
$conn->set_charset("utf8mb4");

ini_set('display_errors', 1);
error_reporting(E_ALL);

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$name || !$phone || !$email || !$password || !$confirm) {
        $errors[] = "Barcha maydonlarni to‘ldiring.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email formati noto‘g‘ri.";
    }

    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        $errors[] = "Telefon raqami noto‘g‘ri formatda.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Parol kamida 6 ta belgidan iborat bo‘lishi kerak.";
    }

    if ($password !== $confirm) {
        $errors[] = "Parollar mos emas.";
    }

    // 🔍 Dublikat tekshiruvi
    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
        $check->bind_param("ss", $phone, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Bu telefon yoki email allaqachon ro‘yxatdan o‘tgan.";
        }

        $check->close();
    }

    // 💾 Baza qo‘shish
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $hashed);

        if ($stmt->execute()) {
            $success = "🎉 Ro‘yxatdan muvaffaqiyatli o‘tdingiz!";
        } else {
            $errors[] = "❌ Ma’lumotni saqlashda xatolik yuz berdi.";
        }

        $stmt->close();
    }
}
?>