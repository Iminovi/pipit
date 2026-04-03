<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'penyuluh') {
    exit;
}

// MEMAKSA STRING '04' MENJADI INTEGER 4
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
$tarif = isset($_GET['tarif']) ? (int)$_GET['tarif'] : 5000;

// Header untuk Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Honor_KB_".$bulan."_".$tahun.".xls");

// QUERY YANG SUDAH DIPERBAIKI
$sql = "SELECT 
            kader_penginput, 
            MAX(nik_kader) as nik, 
            MAX(norek_kader) as norek, 
            COUNT(*) as total 
        FROM warga_kb 
        WHERE MONTH(tanggal_kunjungan) = $bulan 
        AND YEAR(tanggal_kunjungan) = $tahun 
        AND status_bayar = 0 
        GROUP BY kader_penginput";

$query = mysqli_query($koneksi, $sql);
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="font-size: 16px; font-weight: bold; height: 30px; vertical-align: middle;">
                REKAP HONOR KADER - PERIODE <?= $bulan ?> / <?= $tahun ?>
            </th>
        </tr>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th>No</th>
            <th>Nama Kader</th>
            <th>NIK Kader</th>
            <th>Nomor Rekening</th>
            <th>Total Input Data</th>
            <th>Tarif (Rp)</th>
            <th>Total Honor (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        $grand_total = 0;
        
        if (mysqli_num_rows($query) > 0) {
            while($r = mysqli_fetch_assoc($query)): 
                $sub_total = $r['total'] * $tarif;
                $grand_total += $sub_total;
        ?>
        <tr>
            <td align="center"><?= $no++ ?></td>
            <td><?= strtoupper($r['kader_penginput']) ?></td>
            <td>'<?= $r['nik'] ?></td> <td><?= $r['norek'] ?></td>
            <td align="center"><?= $r['total'] ?></td>
            <td align="right"><?= number_format($tarif, 0, ',', '.') ?></td>
            <td align="right" style="font-weight: bold;"><?= number_format($sub_total, 0, ',', '.') ?></td>
        </tr>
        <?php 
            endwhile; 
        } else {
            // PESAN ERROR JIKA DATA TIDAK DITEMUKAN
            echo '<tr><td colspan="7" align="center" style="height: 50px;">
                    Data tidak ditemukan di Database untuk Bulan: '.$bulan.' Tahun: '.$tahun.'
                  </td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr style="background-color: #eee; font-weight: bold;">
            <td colspan="6" align="right">TOTAL KESELURUHAN</td>
            <td align="right">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
        </tr>
    </tfoot>
</table>