<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi sudah dibuat

// Proteksi: Hanya Admin yang boleh masuk
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen User - Admin</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; }
        .btn-add { background: green; }
        .btn-delete { background: red; }
    </style>
</head>
<body>
    <h2>Manajemen Akun (Penyuluh & Kader)</h2>
    <a href="user_tambah.php" class="btn btn-add">+ Tambah User Baru</a>
    <br><br>
    <table>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['username']; ?></td>
            <td><?= $row['nama_lengkap']; ?></td>
            <td><strong><?= strtoupper($row['role']); ?></strong></td>
            <td>
                <a href="user_hapus.php?id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>