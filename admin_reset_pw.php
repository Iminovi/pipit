<?php
session_start();
include 'koneksi.php';

// Satpam: Hanya Admin yang boleh akses file ini
if ($_SESSION['role'] != 'admin') { die("Akses Ditolak!"); }

$id = $_GET['id'];
$password_default = password_hash("123456", PASSWORD_DEFAULT);

$query = mysqli_query($koneksi, "UPDATE users SET password='$password_default' WHERE id='$id'");

if ($query) {
    echo "<script>alert('Password Berhasil di-reset menjadi: 123456'); window.location='admin_users.php';</script>";
} else {
    echo "Gagal reset: " . mysqli_error($koneksi);
}
?>