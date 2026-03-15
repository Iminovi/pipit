<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Penyuluh dan Admin
if ($_SESSION['role'] != 'penyuluh' && $_SESSION['role'] != 'admin') {
    die("Akses ditolak.");
}

// Perintah header agar browser mendownload file excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penyuluhan_KB.xls");

?>

<h2>LAPORAN DATA HASIL PENDATAAN KB</h2>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Kunjungan</th>
            <th>Nama Istri</th>
            <th>Nama Suami</th>
            <th>Jumlah Anak</th>
            <th>Metode Kontrasepsi</th>
            <th>Kader Penginput</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT * FROM warga_kb ORDER BY tanggal_kunjungan DESC");
        while($data = mysqli_fetch_assoc($query)): 
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['tanggal_kunjungan']; ?></td>
            <td><?= $data['nama_istri']; ?></td>
            <td><?= $data['nama_suami']; ?></td>
            <td><?= $data['jumlah_anak']; ?></td>
            <td><?= $data['metode_kontrasepsi']; ?></td>
            <td><?= $data['kader_penginput']; ?></td>
            <td><?= $data['keterangan']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>