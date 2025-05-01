<?php
session_start();
include('C:\xampp\htdocs\resepbiyu123\db\koneksi.php'); // koneksi ke database MySQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek jika email dan password adalah admin manual
    if ($email === 'admin@gmail.com' && $password === '123') {
        $_SESSION['user_id'] = 0;
        $_SESSION['name'] = 'Admin';
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'admin';
        $_SESSION['foto'] = 'admin.jpg'; // default foto admin

        echo "<script>alert('Login sebagai Admin berhasil!'); window.location='admin.php';</script>";
        exit;
    }

    // Cek ke database untuk user biasa
    $email = mysqli_real_escape_string($koneksi, $email);
    $password = mysqli_real_escape_string($koneksi, $password);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['foto'] = $user['foto'];

            if ($user['role'] === 'admin') {
                echo "<script>alert('Login sebagai Admin berhasil!'); window.location='admin.php';</script>";
            } else {
                echo "<script>alert('Login berhasil!'); window.location='index.php';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | ResepBiyu</title>
    <link rel="stylesheet" href="api/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form class="auth-form" method="POST" action="">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Masuk</button>
            <p>Belum punya akun? <a href="signup.php">Daftar di sini</a></p>
        </form>
    </div>
</body>
</html>
