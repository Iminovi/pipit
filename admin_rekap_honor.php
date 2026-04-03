<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Admin atau Penyuluh yang boleh akses
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'penyuluh')) {
    header("Location: login.php");
    exit;
}

$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Tarif dinamis dari input, default 5000
$tarif_per_data = (isset($_GET['tarif']) && $_GET['tarif'] != '') ? $_GET['tarif'] : 5000;

// Query untuk mengambil data honor yang belum dibayar
$sql = "SELECT 
            kader_penginput, 
            nik_kader, 
            norek_kader, 
            COUNT(*) as total_input 
        FROM warga_kb 
        WHERE MONTH(tanggal_kunjungan) = '$bulan_filter' 
        AND YEAR(tanggal_kunjungan) = '$tahun_filter'
        AND status_bayar = 0
        GROUP BY kader_penginput";

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Honor - SIREKAP KB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <style>
        body { background-color: #f8f9fa; font-size: 0.9rem; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 260px; height: 100vh; background: #fff; border-right: 1px solid #dee2e6; position: fixed; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #f1f1f1; text-align: center; }
        .nav-link { color: #333; display: flex; align-items: center; padding: 12px 20px; border-left: 4px solid transparent; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #eef3ff; color: #0d6efd; border-left-color: #0d6efd; }
        .nav-link span { margin-right: 10px; font-size: 20px; }
        .main-content { margin-left: 260px; padding: 20px; }
        .top-navbar { background: #fff; padding: 10px 30px; border-bottom: 1px solid #dee2e6; margin-bottom: 20px; }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header-box { background: #1a4d8f; color: white; border-radius: 5px 5px 0 0; padding: 10px 20px; font-weight: bold; }
        .btn-excel { background: #198754; color: white; border: none; }
        .btn-excel:hover { background: #157347; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-primary fw-bold">SIREKAP<span class="text-warning">KB</span></h4>
    </div>
    <div class="nav flex-column mt-3">
        <div class="px-3 text-muted small fw-bold mb-2">REKAPITULASI</div>
        <a href="dashboard_admin.php" class="nav-link">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a href="admin_rekap_honor.php" class="nav-link active">
            <span class="material-symbols-outlined">payments</span> Rekap Honor
        </a>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">summarize</span> Laporan Umum
        </a>
        <div class="px-3 text-muted small fw-bold mt-4 mb-2">AKUN</div>
        <a href="profil.php" class="nav-link">
            <span class="material-symbols-outlined">person</span> Profil Saya
        </a>
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar d-flex justify-content-between align-items-center">
        <span class="material-symbols-outlined">menu</span>
        <div class="d-flex align-items-center">
            <span class="me-2"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <h5 class="mb-4">💰 Manajemen Honor Kader</h5>

        <div class="card card-custom mb-4">
            <div class="header-box">Filter & Pengaturan Biaya</div>
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <?php
                            for ($m=1; $m<=12; $m++) {
                                $val = str_pad($m, 2, "0", STR_PAD_LEFT);
                                $sel = ($val == $bulan_filter) ? 'selected' : '';
                                echo "<option value='$val' $sel>$val</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold text-muted">Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="<?= $tahun_filter ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted">Biaya per Data (Rp)</label>
                        <input type="number" name="tarif" class="form-control form-control-sm" value="<?= $tarif_per_data ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Terapkan</button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="print_rekap_honor.php?bulan=<?= $bulan_filter ?>&tahun=<?= $tahun_filter ?>&tarif=<?= $tarif_per_data ?>" class="btn btn-excel btn-sm w-100">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">download</span> Simpan 
                        </a>
                    </div>
                    <div class="col-md-6">
        <a href="print_rekap_honor.php?bulan=<?= $bulan_filter ?>&tahun=<?= $tahun_filter ?>&tarif=<?= $tarif_per_data ?>" 
           target="_blank" class="btn btn-danger btn-sm w-100">
           PDF Belum Bayar
        </a>
    </div>
    <div class="col-md-6">
        <a href="print_bukti_bayar.php?bulan=<?= $bulan_filter ?>&tahun=<?= $tahun_filter ?>&tarif=<?= $tarif_per_data ?>" 
           target="_blank" class="btn btn-success btn-sm w-100">
           PDF Sudah Lunas
        </a>
    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom">
            <div class="header-box">Daftar Honor Kader (Belum Dibayar)</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>NO</th>
                                <th>KADER</th>
                                <th>NIK & REKENING</th>
                                <th>TOTAL DATA</th>
                                <th>TOTAL HONOR</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            while($r = mysqli_fetch_assoc($query)): 
                                $total_honor = $r['total_input'] * $tarif_per_data;
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="fw-bold text-primary"><?= $r['kader_penginput'] ?></span></td>
                                <td>
                                    <small class="text-muted d-block">NIK: <?= $r['nik_kader'] ?></small>
                                    <small class="text-muted d-block">REK: <?= $r['norek_kader'] ?></small>
                                </td>
                                <td><span class="badge bg-secondary"><?= $r['total_input'] ?> Data</span></td>
                                <td class="fw-bold text-success">Rp<?= number_format($total_honor, 0, ',', '.') ?></td>
                                <td>
                                    <a href="admin_bayar_honor.php?kader=<?= $r['kader_penginput'] ?>&bulan=<?= $bulan_filter ?>&tahun=<?= $tahun_filter ?>" 
                                       class="btn btn-sm btn-outline-success" 
                                       onclick="return confirm('Tandai honor ini sudah dibayar?')">
                                       Konfirmasi Bayar
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($query) == 0): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Semua honor untuk periode ini sudah lunas atau data belum tersedia.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>