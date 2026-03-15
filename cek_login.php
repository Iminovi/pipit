<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cari user berdasarkan username
    $query  = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $data   = mysqli_fetch_assoc($query);

    // Cek apakah user ada dan password cocok
    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['username']    = $data['username'];
        $_SESSION['nama_lengkap']= $data['nama_lengkap'];
        $_SESSION['role']        = $data['role'];

        // Redirect ke halaman dashboard masing-masing role
        if ($data['role'] == 'admin') {
            header("Location: admin_users.php");
        } elseif ($data['role'] == 'penyuluh') {
            header("Location: penyuluh_laporan.php");
        } else {
            header("Location: kader_input.php");
        }
    } else {
        // Jika salah, balik ke login dengan pesan error
        header("Location: login.php?pesan=gagal");
    }
}
?>