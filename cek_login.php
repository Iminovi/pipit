<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cek apakah koneksi ke DB aman
    if (!$koneksi) { die("Koneksi ke database putus!"); }

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    // DEBUGGING START
    if (!$data) {
        die("Error: Username '$username' TIDAK DITEMUKAN di database. Cek penulisan di tabel users.");
    }

    echo "User ditemukan! Mencoba mencocokkan password... <br>";
    
    if (password_verify($password, $data['password'])) {
        echo "Password COCOK! Mengalihkan...";
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];

        // Redirect sesuai role
        header("Location: dashboard_" . $data['role'] . ".php");
        exit;
    } else {
        echo "Password SALAH! <br>";
        echo "Password yang kamu ketik: " . $password . "<br>";
        echo "Hash di database: " . $data['password'] . "<br>";
        die("Pastikan kamu tidak salah ketik atau salah copy hash di phpMyAdmin.");
    }
    // DEBUGGING END
    if ($data['is_active'] == 0) {
    header("Location: login.php?pesan=pending");
    exit;
}
}
?>