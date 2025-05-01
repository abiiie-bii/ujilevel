<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resepdb");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM resep_makanan WHERE id=$id");
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $conn->real_escape_string($_POST['nama']);
  $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
  $ingredients = json_encode($_POST['ingredients']);
  $steps = json_encode($_POST['steps']);

  $fotoPath = $data['foto'];
  if ($_FILES['foto']['error'] === 0) {
    $targetDir = "image/";
    $fileName = basename($_FILES['foto']['name']);
    $targetFile = $targetDir . time() . "_" . $fileName;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
      $fotoPath = $targetFile;
    }
  }

  $conn->query("UPDATE resep_makanan SET nama='$nama', deskripsi='$deskripsi', foto='$fotoPath', ingredients='$ingredients', steps='$steps' WHERE id=$id");
  header("Location: admin.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Resep</title>
  <link rel="stylesheet" href="api/style.css">
</head>
<body>
  <h1>Edit Resep</h1>
  <form method="POST" enctype="multipart/form-data">
    <label>Nama:
      <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
    </label><br>

    <label>Deskripsi:
      <textarea name="deskripsi" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
    </label><br>

    <label>Ingredients (JSON):
      <textarea name="ingredients[]" rows="4" required><?= htmlspecialchars(json_decode($data['ingredients'])[0] ?? '') ?></textarea>
    </label><br>

    <label>Steps (JSON):
      <textarea name="steps[]" rows="4" required><?= htmlspecialchars(json_decode($data['steps'])[0] ?? '') ?></textarea>
    </label><br>

    <label>Foto:
      <input type="file" name="foto">
      <br><img src="<?= $data['foto'] ?>" width="100">
    </label><br>

    <button type="submit">Simpan Perubahan</button>
    <a href="admin.php">Batal</a>
  </form>
</body>
</html>