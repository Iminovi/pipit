<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'penyuluh') {
    header("Location: login.php");
    exit;
}

$total_warga = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb"))['total'];
$total_kader = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='kader'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyuluh - Sistem KB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <style>
        body { background-color: #f8f9fa; margin: 0; }
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid #dee2e6; position: fixed; overflow-y: auto; z-index: 1000; transition: transform 0.3s; }
        .sidebar.active { transform: translateX(0); }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; }
        .sidebar-header h4 { margin: 0; font-size: 18px; color: #0d6efd; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; transition: 0.3s; text-decoration: none; }
        .nav-link:hover { background: #eef3ff; color: #0d6efd; border-left-color: #0d6efd; }
        .nav-link span { margin-right: 10px; }
        .top-navbar { background: #fff; padding: 12px 30px; border-bottom: 1px solid #dee2e6; margin-left: 260px; display: flex; justify-content: space-between; align-items: center; }
        .hamburger-btn { display: none; background: #0d6efd; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .main-content { margin-left: 260px; padding: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .stat-number { font-size: 32px; font-weight: bold; color: #0d6efd; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 100%; max-width: 260px; }
            .sidebar.active { transform: translateX(0); }
            .top-navbar { margin-left: 0; flex-wrap: wrap; gap: 10px; }
            .main-content { margin-left: 0; padding: 15px; }
            .hamburger-btn { display: flex; }
        }
    </style>
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header"><h4>SIREKAP KB</h4></div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_penyuluh.php" class="nav-link active"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a href="penyuluh_laporan.php" class="nav-link"><span class="material-symbols-outlined">description</span> Laporan</a>
        <a href="dashboard_grafik.php" class="nav-link"><span class="material-symbols-outlined">bar_chart</span> Statistik</a>
        <a href="profil.php" class="nav-link"><span class="material-symbols-outlined">account_circle</span> Profil</a>
        <a href="logout.php" class="nav-link"><span class="material-symbols-outlined">logout</span> Logout</a>
    </div>
</div>
<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn"><span class="material-symbols-outlined">menu</span></button>
    <h5 class="mb-0">Dashboard Penyuluh</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?? 'Penyuluh' ?></span>
</div>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6"><div class="stat-card"><div class="stat-number"><?= $total_warga ?></div><div>Total Warga KB</div></div></div>
            <div class="col-md-6"><div class="stat-card"><div class="stat-number"><?= $total_kader ?></div><div>Total Kader</div></div></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('hamburgerBtn').addEventListener('click', () => document.getElementById('sidebar').classList.toggle('active'));
    document.querySelector('.main-content').addEventListener('click', () => { if (window.innerWidth <= 768) document.getElementById('sidebar').classList.remove('active'); });
</script>
</body>
</html>