<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    // Cek apakah username sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO users (username, nama_lengkap, password, role) 
                                         VALUES ('$username', '$nama_lengkap', '$password', '$role')");
        if ($insert) {
            $success = "User berhasil ditambahkan!";
            header("Refresh: 2; url=admin_users.php");
        } else {
            $error = "Gagal menambahkan user: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Admin</title>
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
        
        .main-content { margin-left: 260px; padding: 20px; }
        .top-navbar { background: #fff; padding: 10px 30px; border-bottom: 1px solid #dee2e6; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .form-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .form-header { background: #0d6efd; color: white; border-radius: 10px 10px 0 0; padding: 20px; }
        .form-body { padding: 30px; }
        .form-label { font-weight: 600; color: #333; margin-bottom: 8px; display: block; }
        .form-control { border: 1px solid #ddd; border-radius: 6px; padding: 10px 12px; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); }
        .btn-submit { background: #0d6efd; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-weight: 600; transition: 0.3s; }
        .btn-submit:hover { background: #0b5ed7; }
        .btn-back { display: inline-block; margin-top: 15px; color: #0d6efd; text-decoration: none; font-weight: 600; }
        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid; }
        .alert-success { background: #d4edda; border-left-color: #198754; color: #155724; }
        .alert-error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-primary fw-bold">bkkbn <span class="text-success">SIREKAP MKJP</span></h4>
        <small class="text-muted">Sistem Informasi Keluarga</small>
    </div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_admin.php" class="nav-link">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>
        <a href="admin_users.php" class="nav-link active">
            <span class="material-symbols-outlined">group</span> User Management
        </a>
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="mb-0">Tambah User Baru</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=0d6efd&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="card form-card" style="max-width: 500px;">
            <div class="form-header">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 24px;">add_circle</span>
                Form Tambah User
            </div>
            <div class="form-body">
                <?php if($success): ?>
                    <div class="alert alert-success">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 18px;">check_circle</span>
                        <?= $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert alert-error">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 18px;">error</span>
                        <?= $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" placeholder="Username unik" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap pengguna" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Password aman" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role / Jabatan *</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="penyuluh">Penyuluh KB</option>
                            <option value="kader">Kader</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="tambah_user" class="btn-submit">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">save</span>
                        Simpan User Baru
                    </button>
                    
                    <a href="admin_users.php" class="btn-back">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">arrow_back</span>
                        Kembali ke Daftar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>