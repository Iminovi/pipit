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
    <title>Edit Data Warga</title>
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
        .form-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-header { background: #1a7d3d; color: white; border-radius: 10px 10px 0 0; padding: 20px; }
        .form-body { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-label { font-weight: 600; color: #333; margin-bottom: 8px; display: block; }
        .form-control { border: 1px solid #ddd; border-radius: 6px; padding: 10px 12px; }
        .form-control:focus { border-color: #198754; box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25); }
        .btn-submit { background: #198754; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-weight: 600; transition: 0.3s; }
        .btn-submit:hover { background: #157347; }
        .btn-back { display: inline-block; margin-top: 15px; color: #198754; text-decoration: none; font-weight: 600; }
        .section-header { color: #495057; font-size: 13px; font-weight: 600; text-transform: uppercase; padding: 10px 20px 5px; margin-top: 15px; }
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
        <h5 class="mb-0">Edit Data Warga</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=198754&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="card form-card">
            <div class="form-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 28px;">edit</span>
                Form Edit Data Warga
            </div>
            <div class="form-body">
                <p class="text-muted">Perbarui data warga sesuai dengan informasi terbaru.</p>
                <hr>
                
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Istri *</label>
                                <input type="text" name="nama_istri" class="form-control" value="<?= $data['nama_istri']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Suami *</label>
                                <input type="text" name="nama_suami" class="form-control" value="<?= $data['nama_suami']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jumlah Anak *</label>
                                <input type="number" name="jumlah_anak" class="form-control" min="0" value="<?= $data['jumlah_anak']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Metode Kontrasepsi *</label>
                                <select name="metode_kb" class="form-control" required>
                                    <option value="Suntik 3 Bulan" <?= $data['metode_kontrasepsi'] == 'Suntik 3 Bulan' ? 'selected' : ''; ?>>Suntik 3 Bulan</option>
                                    <option value="Pil KB" <?= $data['metode_kontrasepsi'] == 'Pil KB' ? 'selected' : ''; ?>>Pil KB</option>
                                    <option value="IUD" <?= $data['metode_kontrasepsi'] == 'IUD' ? 'selected' : ''; ?>>IUD</option>
                                    <option value="Implan" <?= $data['metode_kontrasepsi'] == 'Implan' ? 'selected' : ''; ?>>Implan (Susuk)</option>
                                    <option value="Kondom" <?= $data['metode_kontrasepsi'] == 'Kondom' ? 'selected' : ''; ?>>Kondom</option>
                                    <option value="MOW/MOP" <?= $data['metode_kontrasepsi'] == 'MOW/MOP' ? 'selected' : ''; ?>>MOW / MOP</option>
                                    <option value="Tidak KB" <?= $data['metode_kontrasepsi'] == 'Tidak KB' ? 'selected' : ''; ?>>Tidak KB</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Lokasi (Dusun/RW) *</label>
                                <input type="text" name="lokasi" class="form-control" value="<?= $data['lokasi']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Kunjungan *</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal_kunjungan']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="3"><?= $data['keterangan']; ?></textarea>
                    </div>

                    <button type="submit" name="update_data" class="btn-submit">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">save</span>
                        Perbarui Data Warga
                    </button>
                    
                    <a href="kader_data.php" class="btn-back">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">arrow_back</span>
                        Kembali ke Riwayat
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>