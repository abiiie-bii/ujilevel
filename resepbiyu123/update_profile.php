<?php
session_start();
include('./db/koneksi.php'); // koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);

    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["foto"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Cek jenis file
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFilePath)) {
                $foto = $fileName;
            }
        }
    }

    // Update data di database
    if (!empty($foto)) {
        $sql = "UPDATE users SET name = '$name', foto = '$foto' WHERE id = $userId";
    } else {
        $sql = "UPDATE users SET name = '$name' WHERE id = $userId";
    }

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['name'] = $name;
        if (!empty($foto)) {
            $_SESSION['foto'] = $foto;
        }
    }

    header("Location: index.php");
    exit();
}
?>
