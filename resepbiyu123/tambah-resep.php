<?php
include './db/koneksi.php';

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp_name, 'image/' . $gambar);

    $query = "INSERT INTO resep (nama, deskripsi, gambar) VALUES ('$nama', '$deskripsi', '$gambar')";
    mysqli_query($koneksi, $query);

    header('Location: admin.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Resep</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h1>Tambah Resep Baru</h1>
<form method="post" enctype="multipart/form-data">
    <label>Nama Resep:</label><br>
    <input type="text" name="nama_resep" required><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="deskripsi" rows="5" required></textarea><br><br>

    <label>Gambar:</label><br>
    <input type="file" name="gambar" required><br><br>

    <button type="submit" name="submit">Simpan</button>
</form>

<br>
<a href="admin.php">Kembali</a>

</body>
</html>
