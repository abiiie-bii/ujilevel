<?php
// Include koneksi database
include('./db/koneksi.php');
// Cek jika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Cek jika email sudah ada di database
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location='login.php';</script>";
    } else {
        // Jika email belum ada, masukkan data ke database
        $query_insert = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>alert('Pendaftaran berhasil!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan, coba lagi nanti.'); window.location='signup.php';</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - ResepBiyu</title>
    <link rel="stylesheet" href="api\style.css">
</head>
<body>

    <main>
        <section class="container">
            <h2>Daftar Akun</h2>
            <form method="post" class="auth-form">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" required>

                <label for="email">Email</label>
                <input type="email" name="email" required>

                <label for="password">Kata Sandi</label>
                <input type="password" name="password" required>

                <button type="submit">Daftar</button>
                <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
            </form>
        </section>
    </main>

</body>
</html>
