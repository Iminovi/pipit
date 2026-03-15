<?php
include 'koneksi.php';

$username = 'admin2';
$nama     = 'Administrator Utama';
$role     = 'admin';
// Ganti '12345' dengan password yang kamu mau
$password = password_hash('12345', PASSWORD_DEFAULT); 

$sql = "INSERT INTO users (username, password, nama_lengkap, role) 
        VALUES ('$username', '$password', '$nama', '$role')";

if (mysqli_query($koneksi, $sql)) {
    echo "User Admin Berhasil Dibuat!<br>";
    echo "Username: admin<br>";
    echo "Password: 12345";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>