<?php
$host = "localhost";
$user = "root";     
$pass = "";         
$db   = "penyuluhan_kb";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi jika gagal
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>