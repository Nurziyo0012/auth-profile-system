<?php
// 📌 Fayl: db.php
// 📎 Barcha sahifalarda include qilish uchun yagona baza ulanish fayli

$host = 'localhost';
$username = 'root';
$password = ''; // agar MySQL parol o‘rnatilgan bo‘lsa, shu yerga yozing
$database   = 'hhproject';

// 🔌 Ma’lumotlar bazasiga ulanish
$conn = new mysqli($host, $username, $password, $database);

// ❌ Ulana olmasa — xatolik chiqarish
if ($conn->connect_error) {
    die("Ma’lumotlar bazasiga ulanishda xatolik: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>