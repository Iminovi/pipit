<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'kader') {
    header("Location: login.php"); exit;
}

$user = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM warga_kb WHERE kader_penginput='$user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Saya - Kader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <style>
        body { background-color: #f8f9fa; margin: 0; }
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid #dee2e6; position: fixed; overflow-y: auto; z-index: 1000; transition: transform 0.3s; }
        .sidebar.active { transform: translateX(0); }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; }
        .sidebar-header h4 { margin: 0; font-size: 18px; color: #198754; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; transition: 0.3s; text-decoration: none; }
        .nav-link:hover { background: #e8f5e9; color: #198754; border-left-color: #198754; }
        .nav-link span { margin-right: 10px; }
        .top-navbar { background: #fff; padding: 12px 30px; border-bottom: 1px solid #dee2e6; margin-left: 260px; display: flex; justify-content: space-between; align-items: center; }
        .hamburger-btn { display: none; background: #198754; color: white; border: none; padding: 8px 12px; border-radius: 6px; }
        .main-content { margin-left: 260px; padding: 20px; }
        table { width: 100%; }
        th { background: #198754; color: white; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 100%; max-width: 260px; }
            .sidebar.active { transform: translateX(0); }
            .top-navbar { margin-left: 0; flex-wrap: wrap; }
            .main-content { margin-left: 0; padding: 15px; }
            .hamburger-btn { display: flex; }
        }
    </style>
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4 class="text-success fw-bold">bkkbn <span class="text-success">SIREKAP MKJP</span></h4>
        <small class="text-muted">Sistem Informasi Keluarga</small>
    </div>
    <div class="nav flex-column mt-3">
        <div class="section-header">MENU UTAMA</div>
        <a href="dashboard_kader.php" class="nav-link">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a href="kader_input.php" class="nav-link">
            <span class="material-symbols-outlined">edit_note</span> Input Data
        </a>
        <a href="kader_data.php" class="nav-link active">
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

<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <h5 class="mb-0">Data Saya</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?? 'Kader' ?></span>
</div>

<div class="main-content">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Data Input Saya</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($r=mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$r['lokasi']?></td>
                            <td><?=$r['tanggal_kunjungan']?></td>
                            <td>
                                <a href="edit_data.php?id=<?=$r['id']?>" class="btn btn-sm btn-info">Edit</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
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