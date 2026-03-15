<?php
session_start();
include 'koneksi.php';

// Satpam: Hanya Admin yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil statistik sederhana untuk dashboard
$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"))['total'];
$total_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Sistem KB</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; padding: 20px; box-sizing: border-box; }
        .sidebar h2 { font-size: 20px; border-bottom: 1px solid #555; padding-bottom: 10px; }
        .sidebar a { display: block; color: #bdc3c7; text-decoration: none; padding: 10px 0; transition: 0.3s; }
        .sidebar a:hover { color: white; }
        
        .main-content { flex-grow: 1; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .stats-container { display: flex; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); flex: 1; }
        .card h3 { margin: 0; color: #7f8c8d; font-size: 14px; }
        .card p { margin: 10px 0 0; font-size: 24px; font-weight: bold; color: #2c3e50; }
        
        .btn-logout { background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Sistem KB</h2>
    <nav>
        <a href="dashboard_admin.php">🏠 Dashboard</a>
        <a href="admin_users.php">👥 Kelola User</a>
        <a href="penyuluh_laporan.php">📋 Lihat Semua Laporan</a>
        <a href="dashboard_grafik.php">📊 Statistik Grafik</a>
    </nav>
</div>

<div class="main-content">
    <div class="header">
        <h1>Selamat Datang, <?= $_SESSION['nama_lengkap']; ?>!</h1>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="stats-container">
        <div class="card">
            <h3>Total Pengguna Sistem</h3>
            <p><?= $total_user; ?> Akun</p>
        </div>
        <div class="card">
            <h3>Total Data Warga Terinput</h3>
            <p><?= $total_data; ?> Laporan</p>
        </div>
        <div class="card">
            <h3>Status Server</h3>
            <p style="color: #27ae60;">Online</p>
        </div>
    </div>

    <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 8px;">
        <h3>Panduan Admin</h3>
        <ul>
            <li>Gunakan menu <b>Kelola User</b> untuk menambah Penyuluh atau Kader baru.</li>
            <li>Anda memiliki akses penuh untuk melihat <b>Laporan</b> dan <b>Grafik</b>.</li>
            <li>Pastikan untuk logout setelah selesai menggunakan sistem.</li>
        </ul>
    </div>
</div>

</body>
</html>