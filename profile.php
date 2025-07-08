<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
include 'navbar.php';

$conn = new mysqli("localhost", "root", "", "hhproject");
$conn->set_charset("utf8mb4");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, phone, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $avatar);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>👤 Mening profilim</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-box">
    <h2>👤 Mening profilim</h2>
    
<?php if (!empty($avatar)): ?>
    <img src="uploads/<?= htmlspecialchars($avatar) ?>" width="120" style="border-radius: 50%;">
<?php else: ?>
    <img src="uploads/default_avatar.png" width="120" style="border-radius: 50%;">
<?php endif; ?>

    <?php if (!empty($avatar)): ?>
        <img src="uploads/<?= htmlspecialchars($avatar) ?>" width="120" style="border-radius: 50%;"><br><br>
    <?php else: ?>
        <img src="default_avatar.png" width="120" style="border-radius: 50%;"><br><br>
    <?php endif; ?>

    <p><strong>Ism:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Telefon:</strong> <?= htmlspecialchars($phone) ?></p>

    <br>
    <a href="edit_profile.php">✏️ Tahrirlash</a> |
    <a href="logout.php">🚪 Chiqish</a>
</div>
</body>
</html>