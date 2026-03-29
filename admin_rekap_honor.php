<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Admin atau Penyuluh
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'penyuluh') {
    header("Location: login.php");
    exit;
}

// 1. Ambil data filter dari URL (Default: bulan & tahun sekarang)
$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$tarif_per_data = 5000; // Contoh tarif Rp5.000

// 2. Query dengan Filter Tanggal (MONTH dan YEAR)
$sql = "SELECT 
            kader_penginput, 
            nik_kader, 
            norek_kader, 
            COUNT(*) as total_input 
        FROM warga_kb 
        WHERE MONTH(tanggal_kunjungan) = '$bulan_filter' 
        AND YEAR(tanggal_kunjungan) = '$tahun_filter'
        AND status_bayar = 0
        GROUP BY kader_penginput";

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Rekap Honor Bulanan - SIGA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rekap Honor Kader (Per Bulan)</h5>
            <a href="dashboard_admin.php" class="btn btn-sm btn-light">Dashboard</a>
        </div>
        <div class="card-body">
            
            <form method="GET" class="row g-3 mb-4 p-3 bg-white border rounded">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Pilih Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php
                        $bulan_nama = [
                            '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', 
                            '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', 
                            '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
                        ];
                        foreach ($bulan_nama as $m => $nama) {
                            $selected = ($m == $bulan_filter) ? 'selected' : '';
                            echo "<option value='$m' $selected>$nama</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= $tahun_filter; ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>

            <div class="alert alert-warning py-2">
                Menampilkan data untuk: <b><?= $bulan_nama[$bulan_filter] . " " . $tahun_filter; ?></b>
            </div>
            
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kader</th>
                        <th>NIK</th>
                        <th>Rekening</th>
                        <th class="text-center">Jumlah Input</th>
                        <th>Honor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; $grand_total = 0;
                    while($row = mysqli_fetch_assoc($query)): 
                        $honor = $row['total_input'] * $tarif_per_data;
                        $grand_total += $honor;
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><b><?= $row['kader_penginput']; ?></b></td>
                        <td><small><?= $row['nik_kader']; ?></small></td>
                        <td><?= $row['norek_kader']; ?></td>
                        <td class="text-center"><span class="badge bg-info text-dark"><?= $row['total_input']; ?> Data</span></td>
                        <td class="fw-bold text-success">Rp<?= number_format($honor, 0, ',', '.'); ?></td>
                        <td>
                            <a href="admin_bayar_honor.php?kader=<?= urlencode($row['kader_penginput']); ?>&bulan=<?= $bulan_filter; ?>&tahun=<?= $tahun_filter; ?>" 
                               class="btn btn-sm btn-success" 
                               onclick="return confirm('Tandai honor <?= $row['kader_penginput']; ?> sebagai Lunas?')">
                                Validasi Lunas
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">TOTAL DANA YANG HARUS DIBAYAR:</td>
                        <td colspan="2" class="fs-5 fw-bold text-primary text-center">Rp<?= number_format($grand_total, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <button onclick="window.print()" class="btn btn-outline-secondary mt-3"> Cetak Bukti Pembayaran</button>
        </div>
    </div>
</div>
</body>
</html>