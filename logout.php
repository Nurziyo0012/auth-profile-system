<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include 'navbar.php';
// 📁 logout.php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // yoki login.php
exit;
?>