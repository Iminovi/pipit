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
        $insert = mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama_lengkap', '$role')");
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
    <title>Tambah Pengguna - Admin</title>
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
        <a href="dashboard_admin.php" class="nav-link"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
        <a href="admin_users.php" class="nav-link"><span class="material-symbols-outlined">people</span> Kelola Pengguna</a>
        <a href="user_tambah.php" class="nav-link active"><span class="material-symbols-outlined">person_add</span> Tambah Pengguna</a>
        <a href="penyuluh_laporan.php" class="nav-link"><span class="material-symbols-outlined">description</span> Laporan</a>
        <a href="dashboard_grafik.php" class="nav-link"><span class="material-symbols-outlined">bar_chart</span> Statistik</a>
        <a href="admin_rekap_honor.php" class="nav-link"><span class="material-symbols-outlined">receipt</span> Rekap Honor</a>
        <a href="profil.php" class="nav-link"><span class="material-symbols-outlined">account_circle</span> Profil</a>
        <a href="logout.php" class="nav-link"><span class="material-symbols-outlined">logout</span> Logout</a>
    </div>
</div>

<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn"><span class="material-symbols-outlined">menu</span></button>
    <h5 class="mb-0">Tambah Pengguna</h5>
    <span class="ms-auto">User: <?= $_SESSION['nama_lengkap'] ?? 'Admin' ?></span>
</div>

<div class="main-content">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Formulir Tambah Pengguna</h5>
        </div>
        <div class="card-body">
            <?php if ($error) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if ($success) { echo "<div class='alert alert-success'>$success</div>"; } ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-control" name="role" required>
                        <option value="kader">Kader</option>
                        <option value="penyuluh">Penyuluh</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="tambah_user" class="btn btn-primary">Tambah User</button>
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