<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Penyuluh dan Admin yang boleh akses
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'penyuluh' && $_SESSION['role'] != 'admin')) {
    die("Akses ditolak. Anda tidak memiliki izin untuk mengunduh laporan.");
}

// 1. Ambil parameter filter dari URL (dikirim dari link di penyuluh_laporan.php)
$f_lokasi = isset($_GET['f_lokasi']) ? mysqli_real_escape_string($koneksi, $_GET['f_lokasi']) : '';
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';

// 2. Perintah header agar browser mengenali ini sebagai file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_KB_" . date('Ymd_His') . ".xls");

// 3. Bangun Query SQL yang SAMA PERSIS dengan di halaman laporan
$sql = "SELECT * FROM warga_kb WHERE 1=1";

if ($f_lokasi != '') {
    $sql .= " AND lokasi LIKE '%$f_lokasi%'";
}
if ($tgl_awal != '' && $tgl_akhir != '') {
    $sql .= " AND tanggal_kunjungan BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

$sql .= " ORDER BY tanggal_kunjungan DESC";
$query = mysqli_query($koneksi, $sql);
?>

<center>
    <h2>LAPORAN DATA HASIL PENDATAAN KELUARGA BERENCANA</h2>
    <p><b>BKKBN - Sistem Informasi Keluarga (SIREKAP MKJP)</b></p>
    <p>Laporan Diunduh: <b><?= date('d/m/Y H:i:s'); ?></b></p>
    <?php if($f_lokasi != '') echo "<p>Filter Lokasi: <b>$f_lokasi</b></p>"; ?>
    <?php if($tgl_awal != '') echo "<p>Periode: <b>" . date('d/m/Y', strtotime($tgl_awal)) . "</b> s/d <b>" . date('d/m/Y', strtotime($tgl_akhir)) . "</b></p>"; ?>
</center>

<br>

<table border="1">
    <thead>
        <tr style="background-color: #1a4d8f; color: white;">
            <th><b>No</b></th>
            <th><b>Tanggal Kunjungan</b></th>
            <th><b>Nama Istri</b></th>
            <th><b>Nama Suami</b></th>
            <th><b>Jumlah Anak</b></th>
            <th><b>Lokasi</b></th>
            <th><b>Metode Kontrasepsi</b></th>
            <th><b>Kader Penginput</b></th>
            <th><b>Keterangan</b></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while($data = mysqli_fetch_assoc($query)): 
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= date('d-m-Y', strtotime($data['tanggal_kunjungan'])); ?></td>
            <td><?= $data['nama_istri']; ?></td>
            <td><?= $data['nama_suami']; ?></td>
            <td style="text-align: center;"><?= $data['jumlah_anak']; ?></td>
            <td><?= $data['lokasi']; ?></td>
            <td><?= $data['metode_kontrasepsi']; ?></td>
            <td><?= $data['kader_penginput']; ?></td>
            <td><?= $data['keterangan']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<br><br>
<table>
    <tr>
        <td style="width: 50%; text-align: center; height: 50px;"></td>
        <td style="width: 50%; text-align: center;">
            <p>Tanggal Export: <?= date('d/m/Y'); ?></p>
            <p style="margin-top: 30px;">Admin</p>
            <p>_________________________</p>
        </td>
    </tr>
</table>