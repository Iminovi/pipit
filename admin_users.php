<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi sudah dibuat

// Proteksi: Hanya Admin yang boleh masuk
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
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
        .table-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table-header { background: #0d6efd; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .table-body { padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; font-weight: 600; color: #333; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        tr:hover { background: #f8f9fa; }
        .btn-add { background: #198754; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .btn-add:hover { background: #157347; }
        .btn-delete { background: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; }
        .btn-delete:hover { background: #c82333; }
        .section-header { color: #495057; font-size: 13px; font-weight: 600; text-transform: uppercase; padding: 10px 20px 5px; margin-top: 15px; }
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
        <a href="dashboard_admin.php" class="nav-link">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a href="admin_users.php" class="nav-link active">
            <span class="material-symbols-outlined">group</span> User Management
        </a>
        
        <div class="section-header">LAPORAN</div>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">summarize</span> Rekapitulasi Data
        </a>
        <a href="dashboard_grafik.php" class="nav-link">
            <span class="material-symbols-outlined">bar_chart</span> Statistik Grafik
        </a>
        
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="mb-0">Manajemen User</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=0d6efd&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <a href="user_tambah.php" class="btn-add">
            <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
            Tambah User Baru
        </a>

        <div class="card table-card">
            <div class="table-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 24px;">group</span>
                Daftar Pengguna (Penyuluh & Kader)
            </div>
            <div class="table-body">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['username']; ?></td>
                            <td><?= $row['nama_lengkap']; ?></td>
                            <td>
                                <span class="badge <?= $row['role'] == 'penyuluh' ? 'bg-primary' : 'bg-success'; ?>">
                                    <?= strtoupper($row['role']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="user_hapus.php?id=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>