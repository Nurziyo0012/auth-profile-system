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
session_destroy();
header("Location: login.html");
exit;
?>