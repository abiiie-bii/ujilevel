<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resepdb");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT foto FROM resep_makanan WHERE id=$id");
if ($result && $row = $result->fetch_assoc()) {
  $fotoPath = $row['foto'];
  if (file_exists($fotoPath)) {
    unlink($fotoPath); // hapus file foto
  }
}

$conn->query("DELETE FROM resep_makanan WHERE id=$id");
header("Location: admin.php");
exit();
?>
