<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'kader') {
    header("Location: login.php");
    exit;
}

$user_kader = $_SESSION['username'];
// Hitung berapa data yang sudah diinput oleh kader ini saja
$query_saya = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb WHERE kader_penginput='$user_kader'");
$data_saya = mysqli_fetch_assoc($query_saya)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kader - Sistem KB</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7f6; }
        .navbar { background: #27ae60; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .container { padding: 30px; max-width: 800px; margin: auto; }
        .menu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .menu-card { background: white; padding: 30px; border-radius: 10px; text-align: center; text-decoration: none; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s; }
        .menu-card:hover { transform: translateY(-5px); background: #f9f9f9; }
        .menu-card h3 { margin: 0; color: #27ae60; }
    </style>
</head>
<body>

<div class="navbar">
    <span><b>Sistem KB</b> (Kader: <?= $_SESSION['nama_lengkap']; ?>)</span>
    <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
</div>

<div class="container">
    <h1>Selamat Bekerja, Rekan Kader!</h1>
    <p>Anda telah mendata <b><?= $data_saya; ?></b> warga bulan ini.</p>

    <div class="menu-grid">
        <a href="kader_input.php" class="menu-card">
            <h3>📝 Input Data</h3>
            <p>Tambah data warga hasil kunjungan lapangan.</p>
        </a>
        <a href="penyuluh_laporan.php" class="menu-card">
            <h3>📋 Lihat Data</h3>
            <p>Lihat kembali riwayat data yang sudah dikirim.</p>
        </a>
        <a href="kader_data.php" class="menu-card">
    <h3>📂 Riwayat & Edit</h3>
    <p>Lihat atau ubah data yang pernah diinput.</p>
</a>
    </div>
</div>

</body>
</html>