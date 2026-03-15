<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'kader') {
    header("Location: login.php"); exit;
}

$user = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM warga_kb WHERE kader_penginput='$user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Saya - Kader</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-family: sans-serif; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .btn-edit { background: #f39c12; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>Data Warga yang Saya Input</h2>
    <a href="dashboard_kader.php">⬅ Kembali ke Dashboard</a>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Istri</th>
            <th>Metode KB</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_istri']; ?></td>
            <td><?= $row['metode_kontrasepsi']; ?></td>
            <td>
                <a href="edit_data.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>