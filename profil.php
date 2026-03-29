<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($query);

$error = '';
$success = '';

if (isset($_POST['update_profil'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password_baru = $_POST['password_baru'];
    
    if (!empty($password_baru)) {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama_lengkap', password='$password_hash' WHERE id=$user_id");
    } else {
        $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama_lengkap' WHERE id=$user_id");
    }
    
    if ($update) {
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        $success = "Profil berhasil diperbarui!";
        header("Refresh: 2; url=profil.php");
    } else {
        $error = "Gagal memperbarui profil: " . mysqli_error($koneksi);
    }
}

$role_display = $user['role'] == 'admin' ? 'Administrator' : ($user['role'] == 'penyuluh' ? 'Penyuluh KB' : 'Kader KB');
$role_color = $user['role'] == 'admin' ? 'primary' : ($user['role'] == 'penyuluh' ? 'info' : 'success');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Sistem KB</title>
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
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-header { background: #0d6efd; color: white; border-radius: 10px 10px 0 0; padding: 20px; }
        .card-body { padding: 30px; }
        .profile-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .profile-avatar { width: 120px; height: 120px; margin: 0 auto 15px; }
        .profile-avatar img { width: 100%; height: 100%; }
        .profile-info h5 { font-size: 22px; font-weight: 700; margin-bottom: 5px; }
        .profile-info p { margin: 5px 0; color: #666; }
        .profile-badge { display: inline-block; padding: 6px 15px; border-radius: 20px; color: white; font-size: 12px; font-weight: 600; margin-top: 10px; }
        .form-label { font-weight: 600; color: #333; margin-bottom: 8px; display: block; }
        .form-control { border: 1px solid #ddd; border-radius: 6px; padding: 10px 12px; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); }
        .btn-submit { background: #0d6efd; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .btn-submit:hover { background: #0b5ed7; }
        .btn-cancel { color: #0d6efd; text-decoration: none; margin-left: 10px; }
        .alert { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid; }
        .alert-success { background: #d4edda; border-left-color: #198754; color: #155724; }
        .alert-error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }
        .section-header { color: #495057; font-size: 13px; font-weight: 600; text-transform: uppercase; padding: 10px 20px 5px; margin-top: 15px; }
        .info-group { margin-bottom: 20px; }
        .info-label { font-size: 12px; text-transform: uppercase; color: #999; font-weight: 600; margin-bottom: 5px; }
        .info-value { font-size: 15px; color: #333; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-primary fw-bold">bkkbn <span class="text-success">siga</span></h4>
        <small class="text-muted">Sistem Informasi Keluarga</small>
    </div>
    <div class="nav flex-column mt-3">
        <div class="section-header">MENU</div>
        <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="dashboard_admin.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="admin_users.php" class="nav-link">
                <span class="material-symbols-outlined">group</span> User Management
            </a>
        <?php elseif($_SESSION['role'] == 'penyuluh'): ?>
            <a href="dashboard_penyuluh.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
        <?php else: ?>
            <a href="dashboard_kader.php" class="nav-link">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="kader_input.php" class="nav-link">
                <span class="material-symbols-outlined">edit_note</span> Input Data
            </a>
        <?php endif; ?>
        
        <div class="section-header">LAPORAN</div>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">summarize</span> Rekapitulasi Data
        </a>
        
        <div class="section-header">AKUN</div>
        <a href="profil.php" class="nav-link active">
            <span class="material-symbols-outlined">person</span> Profil Saya
        </a>
        
        <a href="logout.php" class="nav-link mt-5 text-danger">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="mb-0">Profil Pengguna</h5>
        <div class="d-flex align-items-center">
            <span class="me-2 text-muted"><?= $_SESSION['nama_lengkap']; ?></span>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama_lengkap']); ?>&background=0d6efd&color=fff" width="35" class="rounded-circle">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap']); ?>&background=0d6efd&color=fff&size=128" alt="Avatar">
                            </div>
                            <div class="profile-info">
                                <h5><?= $user['nama_lengkap']; ?></h5>
                                <p>@<?= $user['username']; ?></p>
                                <span class="profile-badge bg-<?= $role_color; ?>"><?= $role_display; ?></span>
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">ID Pengguna</div>
                            <div class="info-value"><?= $user['username']; ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value"><?= $user['nama_lengkap']; ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">Peran / Role</div>
                            <div class="info-value"><?= $role_display; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 10px; font-size: 24px;">edit</span>
                        Ubah Profil
                    </div>
                    <div class="card-body">
                        <?php if(!empty($success)): ?>
                            <div class="alert alert-success">
                                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 18px;">check_circle</span>
                                <?= $success; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-error">
                                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 18px;">error</span>
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= $user['nama_lengkap']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">ID Pengguna (tidak bisa diubah)</label>
                                <input type="text" class="form-control" value="<?= $user['username']; ?>" disabled>
                                <small class="text-muted">ID pengguna bersifat permanen dan tidak dapat diubah.</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" name="password_baru" class="form-control" placeholder="Masukkan password baru atau kosongkan">
                                <small class="text-muted">Password minimal 8 karakter. Gunakan kombinasi huruf besar, huruf kecil, dan angka.</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Peran / Role</label>
                                <input type="text" class="form-control" value="<?= $role_display; ?>" disabled>
                                <small class="text-muted">Peran pengguna ditentukan oleh administrator.</small>
                            </div>
                            
                            <div class="form-group" style="margin-top: 30px;">
                                <button type="submit" name="update_profil" class="btn-submit">
                                    <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 8px; font-size: 20px;">save</span>
                                    Simpan Perubahan
                                </button>
                                <a href="javascript:history.back();" class="btn-cancel">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>