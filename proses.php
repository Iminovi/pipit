<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "penyuluhan_kb";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $nik  = $_POST['nik'];
    $kb   = $_POST['metode_kb'];

    $query = "INSERT INTO pendaftar (nama_lengkap, nik, metode_kb) VALUES ('$nama', '$nik', '$kb')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "Data berhasil dikirim!";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>