<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Kader yang boleh masuk
if ($_SESSION['role'] != 'kader') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan_data'])) {
    $istri  = $_POST['nama_istri'];
    $suami  = $_POST['nama_suami'];
    $anak   = $_POST['jumlah_anak'];
    $kb     = $_POST['metode_kb'];
    $tgl    = $_POST['tanggal'];
    $ket    = $_POST['keterangan'];
    $kader  = $_SESSION['username']; // Mengambil info siapa yang input

    $sql = "INSERT INTO warga_kb (nama_istri, nama_suami, jumlah_anak, metode_kontrasepsi, tanggal_kunjungan, kader_penginput, keterangan) 
            VALUES ('$istri', '$suami', '$anak', '$kb', '$tgl', '$kader', '$ket')";
    
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Data Warga Berhasil Disimpan!'); window.location='kader_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Data KB - Kader</title>
</head>
<body>
    <h2>Form Pendataan KB (Kunjungan Lapangan)</h2>
    <form method="POST">
        <label>Nama Istri:</label><br>
        <input type="text" name="nama_istri" required><br><br>

        <label>Nama Suami:</label><br>
        <input type="text" name="nama_suami" required><br><br>

        <label>Jumlah Anak:</label><br>
        <input type="number" name="jumlah_anak" min="0" required><br><br>

        <label>Metode Kontrasepsi yang Digunakan:</label><br>
        <select name="metode_kb" required>
            <option value="">-- Pilih Metode --</option>
            <option value="Suntik 3 Bulan">Suntik 3 Bulan</option>
            <option value="Pil KB">Pil KB</option>
            <option value="IUD">IUD</option>
            <option value="Implan">Implan (Susuk)</option>
            <option value="Kondom">Kondom</option>
            <option value="MOW/MOP">MOW / MOP</option>
            <option value="Tidak KB">Tidak KB (Ingin Hamil/Lainnya)</option>
        </select><br><br>

        <label>Tanggal Kunjungan:</label><br>
        <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required><br><br>

        <label>Keterangan Tambahan:</label><br>
        <textarea name="keterangan" rows="3"></textarea><br><br>

        <button type="submit" name="simpan_data">Simpan Data Warga</button>
        <a href="kader_dashboard.php">Kembali</a>
    </form>
</body>
</html>