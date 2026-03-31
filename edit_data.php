<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'kader') {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_kader = $_SESSION['username'];

// Ambil data warga
$query = mysqli_query($koneksi, "SELECT * FROM warga_kb WHERE id=$id AND kader_penginput='$user_kader'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: kader_data.php");
    exit;
}

if (isset($_POST['update_data'])) {
    $istri = mysqli_real_escape_string($koneksi, $_POST['nama_istri']);
    $suami = mysqli_real_escape_string($koneksi, $_POST['nama_suami']);
    $anak = (int)$_POST['jumlah_anak'];
    $kb = mysqli_real_escape_string($koneksi, $_POST['metode_kb']);
    $tgl = $_POST['tanggal'];
    $ket = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    
    $update = mysqli_query($koneksi, "UPDATE warga_kb SET 
                                       nama_istri='$istri', 
                                       nama_suami='$suami', 
                                       jumlah_anak=$anak, 
                                       metode_kontrasepsi='$kb', 
                                       lokasi='$lokasi', 
                                       tanggal_kunjungan='$tgl', 
                                       keterangan='$ket' 
                                       WHERE id=$id");
    
    if ($update) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='kader_data.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data - Kader</title>
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
        <a href="dashboard_kader.php" class="nav-link"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a href="kader_input.php" class="nav-link"><span class="material-symbols-outlined">add_circle</span> Input Data</a>
        <a href="kader_data.php" class="nav-link active"><span class="material-symbols-outlined">list</span> Data Saya</a>
        <a href="profil.php" class="nav-link"><span class="material-symbols-outlined">account_circle</span> Profil</a>
        <a href="logout.php" class="nav-link"><span class="material-symbols-outlined">logout</span> Logout</a>
    </div>
</div>
<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn"><span class="material-symbols-outlined">menu</span></button>
    <h5 class="mb-0">Edit Data Warga</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?? 'Kader' ?></span>
</div>
<div class="main-content">
    <div class="card">
        <div class="card-header bg-info text-white"><h5 class="mb-0">Formulir Edit Data Warga</h5></div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Istri</label>
                    <input type="text" class="form-control" name="nama_istri" value="<?= $data['nama_istri'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Suami</label>
                    <input type="text" class="form-control" name="nama_suami" value="<?= $data['nama_suami'] ?? '' ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Anak</label>
                    <input type="number" class="form-control" name="jumlah_anak" value="<?= $data['jumlah_anak'] ?? 0 ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Metode KB</label>
                    <input type="text" class="form-control" name="metode_kb" value="<?= $data['metode_kontrasepsi'] ?? '' ?>">
                </div>
                <button type="submit" name="update_data" class="btn btn-info">Update Data</button>
                <a href="kader_data.php" class="btn btn-secondary">Batal</a>
            </form>
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