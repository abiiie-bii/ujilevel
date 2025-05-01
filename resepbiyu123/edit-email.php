<?php
session_start();

//if (!isset($_SESSION['username'])) {
  //header("Location: login.php");
  //exit();
//}

$conn = new mysqli("localhost", "root", "", "resepdb");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari URL
$id = intval($_GET['id']);

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);

  $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");
  header("Location: dashboard.php");
  exit();
}

// Ambil data user saat ini
$result = $conn->query("SELECT * FROM users WHERE id=$id LIMIT 1");
$user = $result->fetch_assoc();

if (!$user) {
  echo "Pengguna tidak ditemukan.";
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Pengguna</title>
  <link rel="stylesheet" href="api/style.css">
</head>
<body>
  <h1>Edit Pengguna</h1>
  <form method="POST">
    <label>Username:
      <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    </label>
    <br>
    <label>Email:
      <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </label>
    <br>
    <button type="submit">Simpan</button>
    <a href="dashboard.php">Batal</a>
  </form>
</body>
</html>