<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="nav-brand">📘 HH Project</div>
    <div class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">👤 Profil</a>
            <a href="logout.php">🚪 Chiqish</a>
        <?php else: ?>
            <a href="login.php">🔐 Kirish</a>
            <a href="register.php">📝 Ro‘yxatdan o‘tish</a>
        <?php endif; ?>
    </div>
</nav>