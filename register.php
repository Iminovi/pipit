<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'kader'; // Otomatis menjadi kader

    // Cek apakah username sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
    } else {
        $sql = "INSERT INTO users (username, password, nama_lengkap, role, is_active) 
                VALUES ('$username', '$pass', '$nama', '$role', 0)";
        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan hubungi Admin untuk aktivasi akun.'); window.location='login.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kader Baru - SIGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
<div class="container">
    <div class="card shadow mx-auto" style="max-width: 400px;">
        <div class="card-body p-4">
            <h4 class="text-center fw-bold text-primary">Daftar Kader</h4>
            <hr>
            <form method="POST">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Username (ID Pengguna)</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">DAFTAR SEKARANG</button>
                <div class="text-center mt-3">
                    <a href="login.php" class="small text-decoration-none">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>