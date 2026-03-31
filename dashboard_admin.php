<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Admin yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil statistik untuk dashboard
$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"))['total'];
$total_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb"))['total'];
$total_kader = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='kader'"))['total'];

// Hitung total honor yang belum dibayar (status_bayar = 0)
$tarif_per_data = 5000;
$honor_result = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb WHERE status_bayar = 0"));
$total_honor_unpaid = $honor_result['total'] * $tarif_per_data;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem KB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body { background-color: #f8f9fa; font-size: 0.9rem; margin: 0; }
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            background: #fff; 
            border-right: 1px solid #dee2e6; 
            position: fixed; 
            overflow-y: auto; 
            z-index: 1000; 
            transition: transform 0.3s ease;
            top: 0;
            left: 0;
        }
        .sidebar.hidden { transform: translateX(-100%); }
        .sidebar.active { transform: translateX(0); }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; text-align: center; }
        .sidebar-header h4 { margin: 0; font-size: 18px; color: #0d6efd; font-weight: 700; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; transition: 0.3s; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #eef3ff; color: #0d6efd; border-left-color: #0d6efd; }
        .nav-link span { margin-right: 10px; font-size: 20px; }
        .section-header { color: #999; font-size: 12px; font-weight: 600; text-transform: uppercase; padding: 15px 20px 5px; }
        .top-navbar { background: #fff; padding: 12px 30px; border-bottom: 1px solid #dee2e6; margin-left: 260px; display: flex; justify-content: space-between; align-items: center; transition: margin-left 0.3s ease; }
        .hamburger-btn { display: none; background: #0d6efd; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .hamburger-btn:hover { background: #0b5ed7; }
        .main-content { margin-left: 260px; padding: 20px; transition: margin-left 0.3s ease; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .stat-number { font-size: 32px; font-weight: bold; color: #0d6efd; }
        .stat-label { color: #6c757d; font-size: 14px; margin-top: 5px; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 100%; max-width: 260px; }
            .sidebar.active { transform: translateX(0); }
            .top-navbar { margin-left: 0; flex-wrap: wrap; gap: 10px; }
            .main-content { margin-left: 0; padding: 15px; }
            .hamburger-btn { display: flex; align-items: center; justify-content: center; }
            .stat-number { font-size: 24px; }
        }
        @media (max-width: 480px) {
            .top-navbar { padding: 10px 15px; font-size: 0.85rem; }
            .main-content { padding: 10px; }
            .stat-card { padding: 15px; }
            .stat-number { font-size: 20px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>SIREKAP KB</h4>
    </div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_admin.php" class="nav-link active">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>
        <a href="admin_users.php" class="nav-link">
            <span class="material-symbols-outlined">people</span>
            Kelola Pengguna
        </a>
        <a href="admin_acc_kader.php" class="nav-link">
            <span class="material-symbols-outlined">people</span>
            Kelola Kader
        </a>
        <a href="user_tambah.php" class="nav-link">
            <span class="material-symbols-outlined">person_add</span>
            Tambah Pengguna
        </a>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">description</span>
            Laporan
        </a>
        <a href="dashboard_grafik.php" class="nav-link">
            <span class="material-symbols-outlined">bar_chart</span>
            Statistik
        </a>
        <a href="admin_rekap_honor.php" class="nav-link">
            <span class="material-symbols-outlined">receipt</span>
            Rekap Honor
        </a>
        <div class="section-header">PROFIL</div>
        <a href="profil.php" class="nav-link">
            <span class="material-symbols-outlined">account_circle</span>
            Profil Saya
        </a>
        <a href="logout.php" class="nav-link">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>
    </div>
</div>

<!-- Top Navbar -->
<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <div>
        <h5 class="mb-0">Dashboard Admin</h5>
    </div>
    <div class="ms-auto">
        <span class="me-3">User: <?= $_SESSION['nama_lengkap'] ?? 'Admin' ?></span>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_user ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_data ?></div>
                    <div class="stat-label">Data Warga KB</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_kader ?></div>
                    <div class="stat-label">Total Kader</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-number">Rp<?= number_format($total_honor_unpaid, 0, ',', '.') ?></div>
                    <div class="stat-label">Honor Belum Bayar</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('hamburgerBtn').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
    
    document.querySelector('.main-content').addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('active');
        }
    });
</script>
</body>
</html>