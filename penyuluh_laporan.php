<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Penyuluh (dan Admin) yang boleh melihat laporan
if ($_SESSION['role'] != 'penyuluh' && $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data dari tabel warga_kb
$query = mysqli_query($koneksi, "SELECT * FROM warga_kb ORDER BY tanggal_kunjungan DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penyuluh KB</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { padding: 5px 8px; border-radius: 4px; font-size: 12px; color: white; }
        .bg-blue { background-color: #3498db; }
    </style>
</head>
<body>
    <a href="export_excel.php" style="padding: 10px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px;">
    📥 Download Excel
</a>
    <h2>Laporan Hasil Pendataan Lapangan</h2>
    <p>Halo, <strong><?= $_SESSION['username']; ?></strong>. Berikut adalah data masuk dari para Kader.</p>
    
    <a href="dashboard_penyuluh.php"> Kembali ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pasangan (Istri/Suami)</th>
                <th>Anak</th>
                <th>Metode KB</th>
                <th>Petugas (Kader)</th>
                <th>Keterangan</th>
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
                <td>
                    <strong><?= $data['nama_istri']; ?></strong> / <br>
                    <small><?= $data['nama_suami']; ?></small>
                </td>
                <td><?= $data['jumlah_anak']; ?></td>
                <td><span class="badge bg-blue"><?= $data['metode_kontrasepsi']; ?></span></td>
                <td><em>@<?= $data['kader_penginput']; ?></em></td>
                <td><?= $data['keterangan']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>