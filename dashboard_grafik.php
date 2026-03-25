<?php
session_start();
include 'koneksi.php';

// Proteksi akses
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

// 1. Ambil data dari database untuk grafik
$label_metode = [];
$jumlah_peserta = [];

$query = mysqli_query($koneksi, "SELECT metode_kontrasepsi, COUNT(*) as total FROM warga_kb GROUP BY metode_kontrasepsi");

while ($row = mysqli_fetch_assoc($query)) {
    $label_metode[] = $row['metode_kontrasepsi'];
    $jumlah_peserta[] = $row['total'];
}

// Tentukan role untuk styling
$role = $_SESSION['role'];
$color_header = ($role == 'penyuluh') ? '#1a5f9f' : ($role == 'admin' ? '#1a4d8f' : '#1a7d3d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Grafik - Sistem KB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        .card-header { background: <?= $color_header ?>; color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .card-body { padding: 30px; }
        .chart-container { position: relative; height: 400px; margin-bottom: 30px; }
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
        <?php else: ?>
            <div class="section-header">DASHBOARD</div>
            <a href="dashboard_penyuluh.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
        <?php endif; ?>
        
        <div class="section-header">LAPORAN</div>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">summarize</span> Rekapitulasi Data
        </a>
        <a href="dashboard_grafik.php" class="nav-link active">
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
        <h5 class="mb-0">Statistik & Analisis Grafik</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=0d6efd&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 24px;">bar_chart</span>
                Penggunaan Metode Kontrasepsi
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="methodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data dari PHP ke Javascript
    const labels = <?php echo json_encode($label_metode); ?>;
    const dataKeluarga = <?php echo json_encode($jumlah_peserta); ?>;

    const ctx = document.getElementById('methodChart').getContext('2d');
    const methodChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pengguna',
                data: dataKeluarga,
                backgroundColor: [
                    '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14'
                ],
                borderColor: [
                    '#0b5ed7', '#157347', '#e0a800', '#bb2d3b', '#5a32a3', '#1aa179', '#f76707'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>