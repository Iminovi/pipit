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
        $success = "Profil berhasil diperbarui!";
        $_SESSION['nama_lengkap'] = $nama_lengkap;
    } else {
        $error = "Gagal memperbarui profil!";
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
    <title>Profil Saya</title>
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
        .hamburger-btn { display: none; background: #0d6efd; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        .main-content { margin-left: 260px; padding: 20px; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 100%; max-width: 260px; }
            .sidebar.active { transform: translateX(0); }
            .top-navbar { margin-left: 0; flex-wrap: wrap; gap: 10px; }
            .main-content { margin-left: 0; padding: 15px; }
            .hamburger-btn { display: flex; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header"><h4>SIREKAP KB</h4></div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_<?= $_SESSION['role'] ?>.php" class="nav-link"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a href="profil.php" class="nav-link active"><span class="material-symbols-outlined">account_circle</span> Profil</a>
        <a href="logout.php" class="nav-link"><span class="material-symbols-outlined">logout</span> Logout</a>
    </div>
</div>

<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn"><span class="material-symbols-outlined">menu</span></button>
    <h5 class="mb-0">Profil Saya</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?? 'User' ?></span>
</div>

<div class="main-content">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Profil</h5>
        </div>
        <div class="card-body">
            <?php if ($error) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if ($success) { echo "<div class='alert alert-success'>$success</div>"; } ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Username:</strong> <?= $user['username'] ?></p>
                    <p><strong>Nama Lengkap:</strong> <?= $user['nama_lengkap'] ?></p>
                    <p><strong>Role:</strong> <span class="badge bg-<?= $role_color ?>"><?= $role_display ?></span></p>
                </div>
            </div>

            <hr>

            <form method="POST">
                <h6>Edit Profil</h6>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru (Kosongkan jika tidak ingin ubah)</label>
                    <input type="password" class="form-control" name="password_baru">
                </div>
                <button type="submit" name="update_profil" class="btn btn-primary">Simpan Perubahan</button>
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