<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'penyuluh')) {
    exit;
}

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
$tarif = isset($_GET['tarif']) ? (int)$_GET['tarif'] : 5000;

// QUERY: Fokus ke status_bayar = 1 (Sudah Lunas)
$sql = "SELECT kader_penginput, MAX(nik_kader) as nik, MAX(norek_kader) as norek, COUNT(*) as total 
        FROM warga_kb 
        WHERE MONTH(tanggal_kunjungan) = $bulan 
        AND YEAR(tanggal_kunjungan) = $tahun 
        AND status_bayar = 1 
        GROUP BY kader_penginput";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Realisasi Honor - LUNAS</title>
    <style>
        body { font-family: 'Arial', sans-serif; padding: 30px; }
        .header { text-align: center; border-bottom: 2px solid #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; font-size: 12px; }
        .lunas-badge { color: green; font-weight: bold; border: 1px solid green; padding: 2px 5px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN REALISASI PEMBAYARAN HONOR</h2>
        <p>Status: <b>SUDAH DIBAYARKAN (LUNAS)</b> | Periode: <?= $bulan ?>/<?= $tahun ?></p>
    </div>

    <table>
        <thead>
            <tr style="background:#eee;">
                <th>No</th>
                <th>Nama Kader</th>
                <th>NIK</th>
                <th>Rekening</th>
                <th>Data</th>
                <th>Total Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; $total_all = 0;
            while($r = mysqli_fetch_assoc($query)): 
                $nominal = $r['total'] * $tarif;
                $total_all += $nominal;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= strtoupper($r['kader_penginput']) ?></td>
                <td><?= $r['nik'] ?></td>
                <td><?= $r['norek'] ?></td>
                <td><?= $r['total'] ?></td>
                <td>Rp <?= number_format($nominal, 0, ',', '.') ?></td>
                <td><span class="lunas-badge">LUNAS</span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>