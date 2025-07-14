<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include 'navbar.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "myproject");

$id = $_SESSION['user_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$new_password = $_POST['new_password'];

// Check for duplicate phone or email used by someone else
$check = $conn->prepare("SELECT id FROM users WHERE (email = ? OR phone = ?) AND id != ?");
$check->bind_param("ssi", $email, $phone, $id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("Phone or email already used by another user.");
}

// Update statement
if (!empty($new_password)) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, password = ? WHERE id = ?");
    $update->bind_param("ssssi", $name, $phone, $email, $hashed, $id);
} else {
    $update = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ? WHERE id = ?");
    $update->bind_param("sssi", $name, $phone, $email, $id);
}

$update->execute();
echo "✅ Profile updated successfully.";
?>