<?php
require 'connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = htmlspecialchars($_POST['username']); // Sanitasi input

    // Cek apakah pengguna sudah terdaftar
    $queryLogin = "SELECT * FROM login WHERE username = '$username'";
    $hasil = mysqli_query($conn, $queryLogin);

    if ($hasil->num_rows == 1) {
        // Pengguna sudah terdaftar, buat sesi dan arahkan ke index
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Pengguna baru, masukkan ke database
        $queryInsert = "INSERT INTO login (username) VALUES ('$username')";
        if (mysqli_query($conn, $queryInsert)) {
            $_SESSION['username'] = $username; // Buat sesi untuk pengguna baru
            echo "<script>alert('Registrasi berhasil! Login sebagai $username');</script>";
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Gagal mendaftarkan user baru!');</script>";
        }
    }
}

// Jika pengguna sudah memiliki sesi, arahkan ke index
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <header>
        <p>Ini Header</p>
    </header>

    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <label for="username">Masukkan Nama</label>
        <input type="text" name="username" id="username" required>
        <button type="submit">Login</button>
    </form>
</body>

</html>