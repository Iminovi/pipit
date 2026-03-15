<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'penyuluh') {
    header("Location: login.php");
    exit;
}

// Statistik singkat untuk penyuluh
$total_warga = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Penyuluh - Sistem KB</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f7f6; }
        .navbar { background: #2980b9; color: white; padding: 15px 20px; display: flex; justify-content: space-between; }
        .container { padding: 30px; }
        .info-box { background: white; padding: 20px; border-left: 5px solid #2980b9; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2980b9; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; }
    </style>
</head>
<body>

<div class="navbar">
    <span><b>Sistem KB</b> (Penyuluh: <?= $_SESSION['nama_lengkap']; ?>)</span>
    <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
</div>

<div class="container">
    <div class="info-box">
        <h2>Ringkasan Laporan</h2>
        <p>Saat ini terdapat <b><?= $total_warga; ?></b> data warga yang masuk dari seluruh Kader.</p>
    </div>

    <div class="actions">
        <a href="penyuluh_laporan.php" class="btn">📋 Buka Laporan Lengkap</a>
        <a href="dashboard_grafik.php" class="btn" style="background: #8e44ad;">📊 Analisis Grafik</a>
        <a href="export_excel.php" class="btn" style="background: #27ae60;">📥 Download Excel</a>
    </div>
</div>

</body>
</html>