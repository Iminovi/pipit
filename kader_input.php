<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Kader yang boleh masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'kader') {
    header("Location: login.php");
    exit;
}

// Proteksi CSRF sederhana
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ambil data terakhir yang pernah diinput oleh kader ini untuk fitur Otomatis Isi
$user_kader = $_SESSION['username'];
$query_profil = mysqli_query($koneksi, "SELECT nik_kader, suami_kader, nik_suami_kader, norek_kader FROM warga_kb WHERE kader_penginput='" . $_SESSION['nama_lengkap'] . "' ORDER BY id DESC LIMIT 1");
$profil_lama = mysqli_fetch_assoc($query_profil);

// Jika belum pernah input, biarkan kosong
$nik_k = $profil_lama['nik_kader'] ?? '';
$suami_k = $profil_lama['suami_kader'] ?? '';
$nik_s_k = $profil_lama['nik_suami_kader'] ?? '';
$norek_k = $profil_lama['norek_kader'] ?? '';

if (isset($_POST['simpan_data'])) {
    // Data Warga
    $istri  = mysqli_real_escape_string($koneksi, $_POST['nama_istri']);
    $suami  = mysqli_real_escape_string($koneksi, $_POST['nama_suami']);
    $anak   = $_POST['jumlah_anak'];
    $kb     = $_POST['metode_kb'];
    $tgl    = $_POST['tanggal'];
    $ket    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $kader  = $_SESSION['nama_lengkap'];
    
    // Data Diri Kader (Tambahan Baru)
    $nik_kader   = mysqli_real_escape_string($koneksi, $_POST['nik_kader']);
    $suami_kader = mysqli_real_escape_string($koneksi, $_POST['suami_kader']);
    $nik_sk      = mysqli_real_escape_string($koneksi, $_POST['nik_suami_kader']);
    $norek       = mysqli_real_escape_string($koneksi, $_POST['norek_kader']);
    
    // Logika Upload Foto
    $nama_foto_baru = ""; // Default jika tidak upload
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $tmp  = $_FILES['foto']['tmp_name'];
        
        // Beri nama unik: tgl_jam_namafile.jpg
        $nama_foto_baru = date('dmYHis') . "_" . $foto;
        $path = "uploads/" . $nama_foto_baru;
        
        move_uploaded_file($tmp, $path);
    }

    // Satu Query untuk semua data (termasuk data kader)
    $sql = "INSERT INTO warga_kb (nama_istri, nama_suami, jumlah_anak, metode_kontrasepsi, lokasi, tanggal_kunjungan, kader_penginput, keterangan, foto_kunjungan, nik_kader, suami_kader, nik_suami_kader, norek_kader) 
            VALUES ('$istri', '$suami', '$anak', '$kb', '$lokasi', '$tgl', '$kader', '$ket', '$nama_foto_baru', '$nik_kader', '$suami_kader', '$nik_sk', '$norek')";
    
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Data Warga Berhasil Disimpan!'); window.location='dashboard_kader.php';</script>";
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
    <title>Input Data KB - Kader</title>
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
        <a href="kader_input.php" class="nav-link active">
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
        <h5 class="mb-0">Input Data Warga KB</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=198754&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="card form-card">
            <div class="form-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 28px;">edit_note</span>
                Form Pendataan KB
            </div>
            <div class="form-body">
                <p class="text-muted">Silakan isi data hasil kunjungan lapangan dengan lengkap dan akurat.</p>
                <hr>
                
                <form method="POST" enctype="multipart/form-data">
                    <!-- SECTION A: DATA WARGA -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                        <h6 style="color: #198754; font-weight: 700; border-bottom: 2px solid #198754; padding-bottom: 10px; margin-bottom: 15px;">
                            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">person_2</span>
                            A. DATA WARGA (SASARAN)
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Istri *</label>
                                    <input type="text" name="nama_istri" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Suami *</label>
                                    <input type="text" name="nama_suami" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Jumlah Anak *</label>
                                    <input type="number" name="jumlah_anak" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Metode Kontrasepsi Jangka Panjang *</label>
                                    <select name="metode_kb" class="form-control" required>
                                        <option value="">-- Pilih Metode MKJP --</option>
                                        <option value="IUD">IUD (Alat Kontrasepsi Dalam Rahim)</option>
                                        <option value="Implan">Implan / Susuk (3 tahun)</option>
                                        <option value="MOW">MOW (Metode Operasi Wanita)</option>
                                        <option value="MOP">MOP (Metode Operasi Pria)</option>
                                        <option value="Tidak KB">Tidak Menggunakan KB</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Lokasi (Dusun/RW) *</label>
                                    <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Dusun Krajan RW 01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Kunjungan *</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan jika ada..."></textarea>
                        </div>
                    </div>

                    <!-- SECTION B: DATA DIRI KADER -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                        <h6 style="color: #198754; font-weight: 700; border-bottom: 2px solid #198754; padding-bottom: 10px; margin-bottom: 15px;">
                            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">badge</span>
                            B. DATA DIRI KADER (PETUGAS)
                        </h6>
                        
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Kader</label>
                            <input type="text" class="form-control" value="<?= $_SESSION['nama_lengkap']; ?>" disabled style="background: #e9ecef;">
                            <small class="text-muted">Data ini terambil dari akun Anda</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">NIK Kader *</label>
                                    <input type="number" name="nik_kader" class="form-control" placeholder="16 digit NIK" value="<?= $nik_k; ?>" required>
                                    <?php if(!empty($nik_k)): ?>
                                        <small class="text-success d-block mt-1">
                                            <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">check_circle</span>
                                            Data otomatis dari input terakhir
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nomor Rekening *</label>
                                    <input type="text" name="norek_kader" class="form-control" placeholder="Contoh: BRI - 1234567890" value="<?= $norek_k; ?>" required>
                                    <?php if(!empty($norek_k)): ?>
                                        <small class="text-success d-block mt-1">
                                            <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">check_circle</span>
                                            Data otomatis dari input terakhir
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nama Suami Kader *</label>
                                    <input type="text" name="suami_kader" class="form-control" value="<?= $suami_k; ?>" required>
                                    <?php if(!empty($suami_k)): ?>
                                        <small class="text-success d-block mt-1">
                                            <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">check_circle</span>
                                            Data otomatis dari input terakhir
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">NIK Suami Kader *</label>
                                    <input type="number" name="nik_suami_kader" class="form-control" placeholder="16 digit NIK" value="<?= $nik_s_k; ?>" required>
                                    <?php if(!empty($nik_s_k)): ?>
                                        <small class="text-success d-block mt-1">
                                            <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">check_circle</span>
                                            Data otomatis dari input terakhir
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION C: DOKUMEN -->
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                        <h6 style="color: #198754; font-weight: 700; border-bottom: 2px solid #198754; padding-bottom: 10px; margin-bottom: 15px;">
                            <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">image</span>
                            C. DOKUMEN KUNJUNGAN
                        </h6>
                        
                        <div class="form-group">
                            <label class="form-label">Upload Foto Kunjungan</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-2">Format: JPG, PNG (Max: 2MB) - Opsional</small>
                        </div>
                    </div>

                    <button type="submit" name="simpan_data" class="btn-submit">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">save</span>
                        Simpan Pendataan
                    </button>
                    
                    <a href="dashboard_kader.php" class="btn-back">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">arrow_back</span>
                        Kembali ke Dashboard
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>