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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Sistem KB</title>
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
        .hamburger-btn { display: none; background: #0d6efd; color: white; border: none; padding: 8px 12px; border-radius: 6px; }
        .main-content { margin-left: 260px; padding: 20px; }
        table { width: 100%; }
        th { background: #0d6efd; color: white; }
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
    <div class="sidebar-header"><h4>SIREKAP KB</h4></div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_penyuluh.php" class="nav-link"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a href="penyuluh_laporan.php" class="nav-link active"><span class="material-symbols-outlined">description</span> Laporan</a>
        <a href="dashboard_grafik.php" class="nav-link"><span class="material-symbols-outlined">bar_chart</span> Statistik</a>
        <a href="profil.php" class="nav-link"><span class="material-symbols-outlined">account_circle</span> Profil</a>
        <a href="logout.php" class="nav-link"><span class="material-symbols-outlined">logout</span> Logout</a>
    </div>
</div>
<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn"><span class="material-symbols-outlined">menu</span></button>
    <h5 class="mb-0">Laporan Data</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?></span>
</div>
<div class="main-content">
    <div class="card mb-3">
        <div class="card-header"><h6 class="mb-0">Filter Data</h6></div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4"><input type="text" name="f_lokasi" class="form-control" placeholder="Lokasi" value="<?=$f_lokasi?>"></div>
                <div class="col-md-4"><input type="date" name="tgl_awal" class="form-control" value="<?=$tgl_awal?>"></div>
                <div class="col-md-4"><input type="date" name="tgl_akhir" class="form-control" value="<?=$tgl_akhir?>"></div>
                <div class="col-12"><button type="submit" class="btn btn-primary btn-sm">Cari</button> <a href="export_excel.php?f_lokasi=<?=$f_lokasi?>&tgl_awal=<?=$tgl_awal?>&tgl_akhir=<?=$tgl_akhir?>" class="btn btn-success btn-sm">Export</a></div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-primary text-white"><h5 class="mb-0">Data Laporan</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>No</th><th>Lokasi</th><th>Tanggal</th><th>Status Bayar</th></tr></thead>
                    <tbody>
                        <?php $no=1; while($r=mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$r['lokasi']?></td>
                            <td><?=$r['tanggal_kunjungan']?></td>
                            <td><span class="badge bg-<?=$r['status_bayar']==1?'success':'warning'?>"><?=$r['status_bayar']==1?'Lunas':'Pending'?></span></td>
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