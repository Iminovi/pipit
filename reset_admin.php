<?php
include 'koneksi.php';

// 1. Hapus user admin yang lama agar tidak bentrok
mysqli_query($koneksi, "DELETE FROM users WHERE username='admin'");

// 2. Buat password baru (admin123)
$username = 'admin';
$password_asli = 'admin123';
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);
$nama = 'Administrator Utama';
$role = 'admin';

// 3. Masukkan ke database
$query = "INSERT INTO users (username, password, nama_lengkap, role) 
          VALUES ('$username', '$password_hash', '$nama', '$role')";

if (mysqli_query($koneksi, $query)) {
    echo "<h2>✅ RESET BERHASIL!</h2>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b><br><br>";
    echo "Silakan hapus file ini demi keamanan, lalu coba <a href='login.php'>Login di sini</a>";
} else {
    echo "Gagal reset: " . mysqli_error($koneksi);
}
?>