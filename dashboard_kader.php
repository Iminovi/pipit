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
        .stat-success { background: linear-gradient(135deg, #198754 0%, #157347 100%); }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 25px; }
        .menu-card { background: white; padding: 20px; border-radius: 10px; text-align: center; text-decoration: none; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: 0.3s; border: 1px solid #eee; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); border-color: #198754; }
        .menu-card h3 { margin: 15px 0 10px; color: #198754; font-size: 18px; }
        .menu-card p { font-size: 13px; color: #666; margin: 0; }
        .menu-card span { font-size: 35px; display: block; }
        .section-header { color: #495057; font-size: 13px; font-weight: 600; text-transform: uppercase; padding: 10px 20px 5px; margin-top: 15px; }
        .search-box { background: #1a7d3d; color: white; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; font-weight: 500; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-success fw-bold">bkkbn <span class="text-success">SIREKAP MKJP</span></h4>
        <small class="text-muted">Sistem Informasi Keluarga</small>
    </div>
    <div class="nav flex-column mt-3">
        <div class="section-header">MENU UTAMA</div>
        <a href="dashboard_kader.php" class="nav-link active">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a href="kader_input.php" class="nav-link">
            <span class="material-symbols-outlined">edit_note</span> Input Data
        </a>
        <a href="kader_data.php" class="nav-link">
            <span class="material-symbols-outlined">history</span> Riwayat Data
        </a>
        
        <div class="section-header">INFORMASI</div>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">summarize</span> Laporan Umum
        </a>
        
        <div class="section-header">AKUN</div>
        <a href="profil.php" class="nav-link">
            <span class="material-symbols-outlined">person</span> Profil Saya
        </a>
        
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="mb-0">Dashboard Kader</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=198754&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stat-box stat-success">
                    <span class="material-symbols-outlined" style="font-size: 40px;">home_work</span>
                    <h3><?= $data_saya; ?></h3>
                    <p>Total Data Warga yang Anda Input</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom" style="padding: 25px;">
                    <h5 style="color: #198754;">📌 Input Terakhir</h5>
                    <?php if($info_terakhir): ?>
                        <p style="margin: 10px 0 5px;"><strong><?= $info_terakhir['lokasi']; ?></strong></p>
                        <small class="text-muted">Tanggal: <?= date('d/m/Y', strtotime($info_terakhir['tanggal_kunjungan'])); ?></small>
                    <?php else: ?>
                        <p class="text-muted">Belum ada data yang diinput</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="search-box">
            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px;">info</span> Menu Akses Cepat
        </div>

        <div class="menu-grid">
            <a href="kader_input.php" class="menu-card">
                <span>📝</span>
                <h3>Input Data Baru</h3>
                <p>Catat hasil kunjungan lapangan dan upload foto bukti.</p>
            </a>

            <a href="kader_data.php" class="menu-card">
                <span>📂</span>
                <h3>Riwayat & Edit</h3>
                <p>Lihat atau perbaiki data warga yang pernah Anda input.</p>
            </a>

            <a href="penyuluh_laporan.php" class="menu-card">
                <span>📋</span>
                <h3>Laporan Umum</h3>
                <p>Lihat rangkuman data penyuluhan secara keseluruhan.</p>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>