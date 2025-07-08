

<nav>
    <ul>
        <li><a href="index.php">🏠 Bosh sahifa</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php">👤 Profil</a></li>
            <li><a href="logout.php">🚪 Chiqish</a></li>
        <?php else: ?>
            <li><a href="login.php">🔐 Kirish</a></li>
            <li><a href="register.php">📝 Ro‘yxatdan o‘tish</a></li>
        <?php endif; ?>
    </ul>
</nav>

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