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

if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM users WHERE id=$id");
  header("Location: dashboard.php");
  exit();
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="api/style.css">
</head>
<body>
  <h1>Selamat Datang,</h1>
  <a href="logout.php">Logout</a>

  <h2>Daftar Pengguna</h2>
  <table border="1">
    <tr>
      <th>ID</th>
      <th>name</th>
      <th>Email</th>
      <th>Aksi</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td>
          <a href="edit-email.php?id=<?php echo $row['id']; ?>">Edit</a> |
          <a href="dashboard.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Hapus pengguna ini?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>
