<?php
session_start();
include 'koneksi.php';

// Proteksi Admin/Penyuluh
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'penyuluh')) {
    exit;
}

// Ambil parameter dan paksa menjadi Integer agar query akurat
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
$tarif = isset($_GET['tarif']) ? (int)$_GET['tarif'] : 5000;

$bulan_nama = [
    1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 
    5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 
    9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
];

// Query: Ambil data yang status_bayar = 0 (Belum Lunas)
$sql = "SELECT kader_penginput, MAX(nik_kader) as nik, MAX(norek_kader) as norek, COUNT(*) as total 
        FROM warga_kb 
        WHERE MONTH(tanggal_kunjungan) = $bulan AND YEAR(tanggal_kunjungan) = $tahun AND status_bayar = 0 
        GROUP BY kader_penginput";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Honor - <?= $bulan_nama[$bulan] ?> <?= $tahun ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; padding: 30px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #eee; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="background: #fff3cd; padding: 10px; margin-bottom: 20px; border: 1px solid #ffeeba;">
        <button onclick="window.print()">🖨️ Klik Cetak / Simpan PDF</button>
        <button onclick="window.close()">❌ Tutup Halaman</button>
    </div>

    <div class="header">
        <h2 style="margin: 0;">REKAPITULASI PEMBAYARAN HONOR KADER</h2>
        <h3 style="margin: 5px 0;">SIREKAP MKJP - BKKBN</h3>
        <p style="margin: 0;">Periode: <b><?= $bulan_nama[$bulan] ?> <?= $tahun ?></b> | Tarif: Rp <?= number_format($tarif, 0, ',', '.') ?> / Data</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kader</th>
                <th>NIK Petugas</th>
                <th>Nomor Rekening</th>
                <th style="text-align: center;">Jumlah Input</th>
                <th style="text-align: right;">Total Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; $grand_total = 0;
            if (mysqli_num_rows($query) > 0) {
                while($r = mysqli_fetch_assoc($query)): 
                    $nominal = $r['total'] * $tarif;
                    $grand_total += $nominal;
            ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= strtoupper($r['kader_penginput']) ?></td>
                <td><?= $r['nik'] ?></td>
                <td><?= $r['norek'] ?></td>
                <td align="center"><?= $r['total'] ?> Data</td>
                <td align="right">Rp <?= number_format($nominal, 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; } else { ?>
                <tr><td colspan="6" align="center">Tidak ada data honor yang perlu dibayarkan pada periode ini.</td></tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" align="right">TOTAL KESELURUHAN</td>
                <td align="right">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i') ?></p>
        <br><br><br>
        <p>( __________________________ )<br>Administrator Utama</p>
    </div>

</body>
</html>