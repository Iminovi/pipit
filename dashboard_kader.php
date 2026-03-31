<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Kader yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'kader') {
    header("Location: login.php");
    exit;
}

$user_kader = $_SESSION['username'];

// Hitung total data yang diinput oleh kader ini
$query_saya = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM warga_kb WHERE kader_penginput='$user_kader'");
$data_saya = mysqli_fetch_assoc($query_saya)['total'];

// Ambil data terakhir yang diinput
$last_entry = mysqli_query($koneksi, "SELECT lokasi, tanggal_kunjungan FROM warga_kb WHERE kader_penginput='$user_kader' ORDER BY id DESC LIMIT 1");
$info_terakhir = mysqli_fetch_assoc($last_entry);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kader - Sistem KB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <style>
        body { background-color: #f8f9fa; margin: 0; }
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid #dee2e6; position: fixed; overflow-y: auto; z-index: 1000; transition: transform 0.3s ease; }
        .sidebar.active { transform: translateX(0); }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; }
        .sidebar-header h4 { margin: 0; font-size: 18px; color: #198754; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; transition: 0.3s; text-decoration: none; }
        .nav-link:hover { background: #e8f5e9; color: #198754; border-left-color: #198754; }
        .nav-link span { margin-right: 10px; }
        .top-navbar { background: #fff; padding: 12px 30px; border-bottom: 1px solid #dee2e6; margin-left: 260px; display: flex; justify-content: space-between; align-items: center; transition: margin-left 0.3s; }
        .hamburger-btn { display: none; background: #198754; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .main-content { margin-left: 260px; padding: 20px; transition: margin-left 0.3s; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .stat-number { font-size: 32px; font-weight: bold; color: #198754; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 100%; max-width: 260px; }
            .sidebar.active { transform: translateX(0); }
            .top-navbar { margin-left: 0; flex-wrap: wrap; gap: 10px; }
            .main-content { margin-left: 0; padding: 15px; }
            .hamburger-btn { display: flex; align-items: center; }
        }
    </style>
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header"><h4>SIREKAP KB</h4></div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_kader.php" class="nav-link active"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a href="kader_input.php" class="nav-link"><span class="material-symbols-outlined">add_circle</span> Input Data</a>
        <a href="kader_data.php" class="nav-link"><span class="material-symbols-outlined">list</span> Data Saya</a>
        <a href="profil.php" class="nav-link"><span class="material-symbols-outlined">account_circle</span> Profil</a>
        <a href="logout.php" class="nav-link"><span class="material-symbols-outlined">logout</span> Logout</a>
    </div>
</div>
<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn"><span class="material-symbols-outlined">menu</span></button>
    <h5 class="mb-0">Dashboard Kader</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?? 'Kader' ?></span>
</div>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6"><div class="stat-card"><div class="stat-number"><?= $data_saya ?></div><div>Total Data Input</div></div></div>
            <div class="col-md-6"><div class="stat-card"><div class="stat-number"><?= $info_terakhir['lokasi'] ?? '-' ?></div><div>Lokasi Terakhir</div></div></div>
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