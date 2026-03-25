<?php
session_start();
include 'koneksi.php';

// Pastikan hanya Admin yang bisa menghapus
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Jangan biarkan admin menghapus dirinya sendiri
if ($id == $_SESSION['id']) {
    header("Location: admin_users.php?error=Tidak bisa menghapus akun sendiri");
    exit;
}

$delete = mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");

if ($delete) {
    header("Location: admin_users.php?success=User berhasil dihapus");
} else {
    header("Location: admin_users.php?error=Gagal menghapus user");
}
exit;
?>