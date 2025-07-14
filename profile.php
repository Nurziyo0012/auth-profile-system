<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once 'auth.php';
require_once 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, phone, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Профиль</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="form-box">
  <h2>👤 Мой профиль</h2>

  <p><strong>Имя:</strong> <?= htmlspecialchars($user['name']) ?></p>
  <p><strong>Телефон:</strong> <?= htmlspecialchars($user['phone']) ?></p>
  <p><strong>Почта:</strong> <?= htmlspecialchars($user['email']) ?></p>

  <a href="edit_profile.php"><button>Редактировать</button></a>
</div>

</body>
</html>