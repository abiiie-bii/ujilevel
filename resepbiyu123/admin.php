<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resepdb");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $conn->real_escape_string($_POST['nama']);
  $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
  $ingredients = json_encode($_POST['ingredients']);
  $steps = json_encode($_POST['steps']);

  $foto = $_FILES['foto'];
  if ($foto['error'] === 0) {
    $targetDir = "image/";
    $fileName = basename($foto['name']);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
      $conn->query("INSERT INTO resep_makanan (nama, deskripsi, foto, ingredients, steps) VALUES ('$nama', '$deskripsi', '$targetFile', '$ingredients', '$steps')");
    }
  }
  header("Location: admin.php");
  exit();
}

$hasil = $conn->query("SELECT * FROM resep_makanan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - CRUD Resep Makanan</title>
  <link rel="stylesheet" href="api/style.css">
</head>
<body>
<center>
<h1>Admin - CRUD Resep</h1>
</center>
<center>
  <div class="container">
  <h2>Tambah Resep</h2>
  <form method="POST" enctype="multipart/form-data">
    <div>
      <label for="nama">Nama Makanan:</label>
      <input type="text" id="nama" name="nama" required>
    </div>

    <div>
      <label for="deskripsi">Deskripsi:</label>
      <textarea id="deskripsi" name="deskripsi" rows="3" required></textarea>
    </div>

    <div>
      <label for="ingredients">Bahan-bahan (1 baris 1 bahan):</label>
      <textarea id="ingredients" name="ingredients[]" rows="4" required></textarea>
    </div>

    <div>
      <label for="steps">Langkah-langkah (1 baris 1 langkah):</label>
      <textarea id="steps" name="steps[]" rows="4" required></textarea>
    </div>

    <div>
      <label for="foto">Foto:</label>
      <input type="file" id="foto" name="foto" accept="image/*" required>
    </div>

    <button type="submit">Tambah Resep</button>
  </form>
</div>
</center>

  <h2>Daftar Resep</h2>
  <table>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Deskripsi</th>
      <th>Foto</th>
      <th>Aksi</th>
    </tr>
    <?php $no = 1; while ($row = $hasil->fetch_assoc()): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
        <td><img src="<?= $row['foto'] ?>" width="100"></td>
        <td>
          <a href="edit-resep.php?id=<?= $row['id'] ?>">Edit</a> |
          <a href="hapus-resep.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus resep ini?')">Hapus</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
