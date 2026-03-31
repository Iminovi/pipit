<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // Validasi password
    if ($password !== $password_confirm) {
        $error = 'Password tidak cocok!';
    } else if (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        // Cek username sudah ada
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap, role, is_active) VALUES ('$username', '$password_hash', '$nama_lengkap', 'kader', 0)");
            
            if ($insert) {
                $success = 'Pendaftaran berhasil! Tunggu persetujuan dari admin.';
            } else {
                $error = 'Gagal mendaftar: ' . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIREKAP MKJP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-container { background: white; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); padding: 40px; width: 100%; max-width: 450px; }
        .register-header { text-align: center; margin-bottom: 30px; }
        .register-header h2 { color: #667eea; font-weight: bold; }
        .form-control { border-radius: 5px; margin-bottom: 15px; padding: 12px; }
        .btn-register { width: 100%; padding: 12px; font-weight: bold; border-radius: 5px; }
        .form-text { font-size: 12px; }
        @media (max-width: 480px) {
            .register-container { padding: 20px; margin: 15px; }
            .register-header h2 { font-size: 24px; }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-header">
        <h2>SIREKAP KB</h2>
        <p class="text-muted">Daftar Akun Kader</p>
    </div>
    
    <?php if ($error) { echo "<div class='alert alert-danger alert-dismissible fade show'><strong>Error!</strong> $error <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; } ?>
    <?php if ($success) { echo "<div class='alert alert-success alert-dismissible fade show'><strong>Sukses!</strong> $success <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>"; } ?>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap Anda" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username (minimal 4 karakter)" required>
            <small class="form-text text-muted">Gunakan huruf, angka, dan underscore</small>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password (minimal 6 karakter)" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_confirm" placeholder="Ulang Password" required>
        </div>
        
        <button type="submit" name="register" class="btn btn-primary btn-register">Daftar</button>
    </form>
    
    <hr>
    <p class="text-center text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-primary fw-bold">Login di sini</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>