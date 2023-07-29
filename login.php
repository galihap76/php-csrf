<?php
session_start();

include "db.php";
?>

<html>

<head>
    <title>Login</title>
</head>

<body>
    <form method="post">
        <ul>
            <li style="margin-bottom:20px;">
                <label for="username">Username :</label>
                <input type="text" id="username" name="username" autocomplete="off" required />
            </li>

            <li style="margin-bottom:20px;">
                <label for="password">Password :</label>
                <input type="password" id="password" name="password" autocomplete="off" required />
            </li>

            <li>
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <button type="submit" name="kirim">Kirim!</button>
            </li>
        </ul>
    </form>

    <!-- Php script -->
    <?php

    // Fungsi untuk menghasilkan token CSRF acak
    function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Fungsi untuk memeriksa kecocokan token CSRF yang dikirimkan dengan token di sesi
    function isCSRFTokenValid($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Cek jika metode yang digunakan adalah POST dan tombol kirim telah di tekan
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim'])) {

        // Cek jika token CSRF yang dikirimkan tidak valid
        if (!isCSRFTokenValid($_POST['csrf_token'])) {

            // Alihkan ke kode HTTP 403
            header("HTTP/1.1 403 Forbidden");
            die();

            // Misalkan token CSRF itu valid
        } else {

            // Lanjutkan dengan memproses login
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Proses login dan lakukan validasi
            $sql = "SELECT * FROM tbl_auth WHERE username = :username AND password = :password";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("username", $username);
            $stmt->bindParam("password", $password);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Jika autentikasi login nya itu valid
            if ($res) {

                // Set session
                $_SESSION['login'] = true;

                //Alihkan ke halaman index.php
                header('Location: index.php');

                // Jika gagal login
            } else {

                // Beri pesan
                echo "Maaf login Anda tidak valid!";
            }
        }
    }
    ?>
    <!-- </End php script -->

</body>

</html>