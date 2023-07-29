<?php
session_start();

// Jika belum login
if (!isset($_SESSION['login'])) {

    // Paksa untuk login
    header('Location: login.php');
    die();

    // Jika berhasil login
} else {

    // Set session regenerate ID
    session_regenerate_id();
}
?>

<html>

<head>
    <title>App</title>
</head>

<body>
    <p>Selamat datang galih!</p>
</body>

</html>