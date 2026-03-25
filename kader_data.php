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
        body { background-color: #f8f9fa; font-size: 0.9rem; }
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid #dee2e6; position: fixed; overflow-y: auto; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; text-align: center; }
        .sidebar-header h4 { margin: 0; font-size: 18px; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #eef3ff; color: #198754; border-left-color: #198754; }
        .nav-link span { margin-right: 10px; font-size: 20px; }
        .nav-link.text-danger:hover { background: #ffe0e0; }
        
        .main-content { margin-left: 260px; padding: 20px; }
        .top-navbar { background: #fff; padding: 10px 30px; border-bottom: 1px solid #dee2e6; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .table-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table-header { background: #1a7d3d; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .table-body { padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; font-weight: 600; color: #333; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        tr:hover { background: #f8f9fa; }
        .btn-edit { background: #0d6efd; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; }
        .btn-edit:hover { background: #0b5ed7; }
        .section-header { color: #495057; font-size: 13px; font-weight: 600; text-transform: uppercase; padding: 10px 20px 5px; margin-top: 15px; }
        .empty-message { text-align: center; padding: 40px; color: #999; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-success fw-bold">bkkbn <span class="text-success">siga</span></h4>
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
        
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="mb-0">Riwayat Pendataan Saya</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=198754&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="card table-card">
            <div class="table-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 24px;">history</span>
                Data Warga yang Telah Diinput
            </div>
            <div class="table-body">
                <?php if(mysqli_num_rows($query) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Istri / Suami</th>
                                <th>Lokasi</th>
                                <th>Metode KB</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_kunjungan'])); ?></td>
                                <td><strong><?= $row['nama_istri']; ?></strong> / <?= $row['nama_suami']; ?></td>
                                <td><?= $row['lokasi']; ?></td>
                                <td><span class="badge bg-success"><?= $row['metode_kontrasepsi']; ?></span></td>
                                <td>
                                    <a href="edit_data.php?id=<?= $row['id']; ?>" class="btn-edit">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        <span class="material-symbols-outlined" style="font-size: 60px; color: #ddd;">inbox</span>
                        <p>Belum ada data yang diinput.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>