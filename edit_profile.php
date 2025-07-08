<?php
session_start();
include 'navbar.php';

$conn = new mysqli("localhost", "root", "", "hhproject");
$conn->set_charset("utf8mb4");

// Foydalanuvchining login holatini tekshiramiz
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$errors = [];
$success = "";

// Ma’lumotlarni olish
$query = $conn->prepare("SELECT name, email, phone, avatar FROM users WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$query->bind_result($name, $email, $phone, $avatar);
$query->fetch();
$query->close();

// Agar forma yuborilsa:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim(strip_tags($_POST['name'] ?? ''));
    $newEmail = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $newPhone = trim($_POST['phone'] ?? '');
    $newPass = $_POST['password'] ?? '';
    $confirmPass = $_POST['confirm'] ?? '';

    // 🖼 Avatar yuklash
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['avatar']['type'], $allowed)) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $newAvatar = uniqid("avatar_", true) . '.' . $ext;
            if (!is_dir("uploads")) {
                mkdir("uploads");
            }
            move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/" . $newAvatar);
            $avatar = $newAvatar;
        } else {
            $errors[] = "Faqat JPEG yoki PNG formatdagi rasm yuklang.";
        }
    }

    // 📋 Validatsiyalar
    if ($newEmail == '') {
        $errors[] = 'Email kiritilmadi';
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email noto‘g‘ri formatda';
    }

    if ($newPhone == '') {
        $errors[] = 'Telefon raqami kiritilmadi';
    } elseif (!preg_match('/^\+?[0-9]{9,15}$/', $newPhone)) {
        $errors[] = 'Telefon raqami noto‘g‘ri formatda';
    }

    // Email yoki telefon boshqa foydalanuvchiga tegishli emasligini tekshiramiz
    $check = $conn->prepare("SELECT id FROM users WHERE (email = ? OR phone = ?) AND id != ?");
    $check->bind_param("ssi", $newEmail, $newPhone, $userId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $errors[] = "Bunday email yoki telefon allaqachon mavjud.";
    }
    $check->close();

    // Parolni tekshiramiz
    if ($newPass !== '' || $confirmPass !== '') {
        if (strlen($newPass) < 6) {
            $errors[] = "Parol kamida 6 ta belgidan iborat bo‘lishi kerak.";
        } elseif ($newPass !== $confirmPass) {
            $errors[] = "Parollar mos emas.";
        }
    }

    // ✅ Barchasi to‘g‘ri bo‘lsa — yangilaymiz
    if (empty($errors)) {
        if ($newPass !== '') {
            $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, avatar = ?, password = ? WHERE id = ?");
            $update->bind_param("sssssi", $newName, $newEmail, $newPhone, $avatar, $hashedPass, $userId);
        } else {
            $update = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, avatar = ? WHERE id = ?");
            $update->bind_param("ssssi", $newName, $newEmail, $newPhone, $avatar, $userId);
        }

        if ($update->execute()) {
            $success = "✅ Ma’lumotlar muvaffaqiyatli yangilandi!";
            $name = $newName;
            $email = $newEmail;
            $phone = $newPhone;
        } else {
            $errors[] = "❌ Yangilashda xatolik yuz berdi.";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Profilni tahrirlash</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-box">
    <h2>🔧 Profil ma’lumotlarini tahrirlash</h2>

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

🦋, [7/8/2025 2:17 PM]
<?php if (!empty($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Ism:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label>Telefon:</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required><br><br>

        <label>Yangi parol (ixtiyoriy):</label><br>
        <input type="password" name="password"><br><br>

        <label>Parol tasdiqlang:</label><br>
        <input type="password" name="confirm"><br><br>

        <label>Profil rasmi (ixtiyoriy):</label><br>
        <?php if (!empty($avatar)): ?>
            <img src="uploads/<?= htmlspecialchars($avatar) ?>" width="100" style="border-radius: 50%;"><br>
        <?php endif; ?>
        <input type="file" name="avatar"><br><br>

        <button type="submit">💾 Saqlash</button>
    </form>

    <br><a href="profile.php">🔙 Orqaga — profil sahifasiga</a>
</div>
</body>
</html>