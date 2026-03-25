<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'penyuluh') {
    header("Location: login.php");
    exit;
}

// Statistik singkat untuk penyuluh
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
        body { background-color: #f8f9fa; font-size: 0.9rem; }
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid #dee2e6; position: fixed; overflow-y: auto; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; text-align: center; }
        .sidebar-header h4 { margin: 0; font-size: 18px; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #eef3ff; color: #0d6efd; border-left-color: #0d6efd; }
        .nav-link span { margin-right: 10px; font-size: 20px; }
        .nav-link.text-danger:hover { background: #ffe0e0; }
        
        .main-content { margin-left: 260px; padding: 20px; }
        .top-navbar { background: #fff; padding: 10px 30px; border-bottom: 1px solid #dee2e6; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .stat-box { padding: 25px; border-radius: 10px; color: white; text-align: center; }
        .stat-box h3 { font-size: 32px; margin: 10px 0; font-weight: bold; }
        .stat-box p { margin: 0; font-size: 14px; opacity: 0.9; }
        .stat-info { background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); }
        .stat-success { background: linear-gradient(135deg, #198754 0%, #157347 100%); }
        .search-box { background: #1a5f9f; color: white; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; font-weight: 500; }
        .section-header { color: #495057; font-size: 13px; font-weight: 600; text-transform: uppercase; padding: 10px 20px 5px; margin-top: 15px; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 25px; }
        .menu-card { background: white; padding: 20px; border-radius: 10px; text-align: center; text-decoration: none; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s; border: 1px solid #eee; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); border-color: #0d6efd; }
        .menu-card h3 { margin: 15px 0 10px; color: #0d6efd; font-size: 18px; }
        .menu-card p { font-size: 13px; color: #666; margin: 0; }
        .menu-card span { font-size: 35px; display: block; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-primary fw-bold">bkkbn <span class="text-success">SIREKAP MKJP</span></h4>
        <small class="text-muted">Sistem Informasi Keluarga</small>
    </div>
    <div class="nav flex-column mt-3">
        <div class="section-header">DASHBOARD</div>
        <a href="dashboard_penyuluh.php" class="nav-link active">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        
        <div class="section-header">LAPORAN</div>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">summarize</span> Rekapitulasi Data
        </a>
        <a href="dashboard_grafik.php" class="nav-link">
            <span class="material-symbols-outlined">bar_chart</span> Statistik Grafik
        </a>
        <a href="export_excel.php" class="nav-link">
            <span class="material-symbols-outlined">download</span> Download Excel
        </a>
        
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="mb-0">Dashboard Penyuluh</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=0d6efd&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="stat-box stat-info">
                    <span class="material-symbols-outlined" style="font-size: 40px;">home_work</span>
                    <h3><?= $total_warga; ?></h3>
                    <p>Total Data Warga Terinput</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="stat-box stat-success">
                    <span class="material-symbols-outlined" style="font-size: 40px;">group</span>
                    <h3><?= $total_kader; ?></h3>
                    <p>Total Kader Aktif</p>
                </div>
            </div>
        </div>

        <div class="search-box">
            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">info</span> Menu Akses Cepat
        </div>

        <div class="menu-grid">
            <a href="penyuluh_laporan.php" class="menu-card">
                <span>📋</span>
                <h3>Rekapitulasi Data</h3>
                <p>Lihat semua laporan dari seluruh Kader dalam satu tampilan.</p>
            </a>

            <a href="dashboard_grafik.php" class="menu-card">
                <span>📊</span>
                <h3>Statistik Grafik</h3>
                <p>Analisis visual data penyuluhan keluarga berencana.</p>
            </a>

            <a href="export_excel.php" class="menu-card">
                <span>📥</span>
                <h3>Download Excel</h3>
                <p>Ekspor data laporan ke format Excel untuk keperluan analisis.</p>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>