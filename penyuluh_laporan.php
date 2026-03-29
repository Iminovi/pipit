<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Penyuluh (dan Admin) yang boleh melihat laporan
if ($_SESSION['role'] != 'penyuluh' && $_SESSION['role'] != 'admin'  && $_SESSION['role'] != 'kader') {
    header("Location: login.php");
    exit;
}

// 1. Menangkap data filter dari Form
$f_lokasi = isset($_GET['f_lokasi']) ? mysqli_real_escape_string($koneksi, $_GET['f_lokasi']) : '';
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';

// 2. Membangun Query SQL Dinamis sesuai filter
$sql = "SELECT * FROM warga_kb WHERE 1=1"; // 1=1 agar penyambungan 'AND' lebih mudah

if ($f_lokasi != '') {
    $sql .= " AND lokasi LIKE '%$f_lokasi%'";
}
if ($tgl_awal != '' && $tgl_akhir != '') {
    $sql .= " AND tanggal_kunjungan BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

$sql .= " ORDER BY tanggal_kunjungan DESC";
$query = mysqli_query($koneksi, $sql);

// Tentukan role warna dan sidebar
$role = $_SESSION['role'];
$color_sidebar = ($role == 'penyuluh') ? '#0d6efd' : ($role == 'admin' ? '#0d6efd' : '#198754');
$color_header = ($role == 'penyuluh') ? '#1a5f9f' : ($role == 'admin' ? '#1a4d8f' : '#1a7d3d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyuluh KB</title>
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
        .table-header { background: <?= $color_header ?>; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .table-body { padding: 20px; }
        .filter-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .filter-box input, .filter-box select { padding: 8px 10px; border: 1px solid #ddd; border-radius: 6px; margin-right: 8px; font-size: 0.9rem; }
        .filter-box button { background: <?= $color_header ?>; color: white; padding: 8px 20px; border: none; border-radius: 6px; cursor: pointer; }
        .filter-box button:hover { opacity: 0.9; }
        .filter-box a { color: <?= $color_header ?>; text-decoration: none; font-weight: 600; margin-left: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; font-weight: 600; color: #333; padding: 12px; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        tr:hover { background: #f8f9fa; }
        .badge-kb { padding: 6px 10px; border-radius: 4px; font-size: 12px; color: white; background: #0d6efd; }
        .btn-export { background: #198754; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-export:hover { background: #157347; }
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
        <?php if($role == 'admin'): ?>
            <div class="section-header">DASHBOARD</div>
            <a href="dashboard_admin.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <div class="section-header">KELOLA</div>
            <a href="admin_users.php" class="nav-link">
                <span class="material-symbols-outlined">group</span> User Management
            </a>
        <?php elseif($role == 'penyuluh'): ?>
            <div class="section-header">DASHBOARD</div>
            <a href="dashboard_penyuluh.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
        <?php else: ?>
            <div class="section-header">MENU UTAMA</div>
            <a href="dashboard_kader.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
        <?php endif; ?>
        
        <div class="section-header">LAPORAN</div>
        <a href="penyuluh_laporan.php" class="nav-link active">
            <span class="material-symbols-outlined">summarize</span> Rekapitulasi Data
        </a>
        <?php if($role != 'kader'): ?>
            <a href="dashboard_grafik.php" class="nav-link">
                <span class="material-symbols-outlined">bar_chart</span> Statistik Grafik
            </a>
            <a href="export_excel.php?f_lokasi=<?= $f_lokasi ?>&tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>" class="nav-link">
                <span class="material-symbols-outlined">download</span> Download Excel
            </a>
        <?php endif; ?>
        
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
        <h5 class="mb-0">Laporan Pendataan Lapangan</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=0d6efd&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="card table-card">
            <div class="table-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 24px;">summarize</span>
                Laporan Hasil Pendataan Lapangan
            </div>
            <div class="table-body">
                <div class="filter-box">
                    <form method="GET" action="">
                        <strong>Filter Data:</strong><br><br>
                        <input type="text" name="f_lokasi" placeholder="Cari Lokasi/RW" value="<?= $f_lokasi; ?>" style="width: 200px;">
                        <input type="date" name="tgl_awal" value="<?= $tgl_awal; ?>"> 
                        <span style="margin: 0 5px;">s/d</span>
                        <input type="date" name="tgl_akhir" value="<?= $tgl_akhir; ?>">
                        <button type="submit">Terapkan Filter</button>
                        <a href="penyuluh_laporan.php">
                            <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">refresh</span>
                        </a>
                    </form>
                </div>

                <a href="export_excel.php?f_lokasi=<?= $f_lokasi ?>&tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>" class="btn-export">
                    <span class="material-symbols-outlined" style="font-size: 20px;">download</span>
                    Download Excel
                </a>

                <table style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pasangan</th>
                            <th>Lokasi</th>
                            <th>Metode KB</th>
                            <th>Foto</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($data = mysqli_fetch_assoc($query)): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d/m/Y', strtotime($data['tanggal_kunjungan'])); ?></td>
                            <td>
                                <strong><?= $data['nama_istri']; ?></strong><br>
                                <small class="text-muted"><?= $data['nama_suami']; ?></small>
                            </td>
                            <td><?= $data['lokasi']; ?></td>
                            <td><span class="badge-kb"><?= $data['metode_kontrasepsi']; ?></span></td>
                            <td>
                                <?php if (!empty($data['foto_kunjungan'])): ?>
                                    <a href="uploads/<?= $data['foto_kunjungan']; ?>" target="_blank">
                                        <img src="uploads/<?= $data['foto_kunjungan']; ?>" width="50" style="border-radius: 4px; cursor: pointer;">
                                    </a>
                                <?php else: ?>
                                    <small style="color: #999;">-</small>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted">@<?= $data['kader_penginput']; ?></small></td>
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