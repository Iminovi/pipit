<?php
session_start();
include 'koneksi.php';

// Proteksi akses
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }

// 1. Ambil data dari database untuk grafik
$label_metode = [];
$jumlah_peserta = [];

$query = mysqli_query($koneksi, "SELECT metode_kontrasepsi, COUNT(*) as total FROM warga_kb GROUP BY metode_kontrasepsi");

while ($row = mysqli_fetch_assoc($query)) {
    $label_metode[] = $row['metode_kontrasepsi'];
    $jumlah_peserta[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visualisasi Data KB</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; text-align: center; }
        .chart-container { width: 400px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .nav { margin-bottom: 20px; padding: 10px; background: #34495e; }
        .nav a { color: white; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>

<div class="nav">
    <a href="penyuluh_laporan.php">Lihat Tabel</a>
    <a href="logout.php" style="color: #ff7675;">Logout</a>
</div>

<h2>Statistik Penggunaan Metode KB</h2>

<div class="chart-container">
    <canvas id="myChart"></canvas>
</div>

<script>
    // Data dari PHP ke Javascript
    const labels = <?php echo json_encode($label_metode); ?>;
    const dataKeluarga = <?php echo json_encode($jumlah_peserta); ?>;

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie', // Bisa diganti 'bar' jika ingin grafik batang
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pengguna',
                data: dataKeluarga,
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0', '#f67019'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

</body>
</html>