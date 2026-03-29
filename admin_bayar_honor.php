<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') { die("Akses Ditolak!"); }

$kader = $_GET['kader'];
$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

// Update semua data warga yang diinput kader tersebut pada bulan & tahun terkait menjadi Lunas (1)
$sql = "UPDATE warga_kb SET status_bayar = 1 
        WHERE kader_penginput = '$kader' 
        AND MONTH(tanggal_kunjungan) = '$bulan' 
        AND YEAR(tanggal_kunjungan) = '$tahun'";

if (mysqli_query($koneksi, $sql)) {
    echo "<script>alert('Pembayaran untuk $kader Berhasil Divalidasi!'); window.location='admin_rekap_honor.php?bulan=$bulan&tahun=$tahun';</script>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>