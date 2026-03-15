<?php
session_start();
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM warga_kb WHERE id='$id'"));

if (isset($_POST['update'])) {
    $istri  = $_POST['nama_istri'];
    $kb     = $_POST['metode_kb'];
    $anak   = $_POST['jumlah_anak'];

    $sql = "UPDATE warga_kb SET nama_istri='$istri', metode_kontrasepsi='$kb', jumlah_anak='$anak' WHERE id='$id'";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: kader_data.php");
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Data KB</title></head>
<body>
    <h2>Edit Data Warga</h2>
    <form method="POST">
        <label>Nama Istri:</label><br>
        <input type="text" name="nama_istri" value="<?= $data['nama_istri']; ?>" required><br><br>

        <label>Jumlah Anak:</label><br>
        <input type="number" name="jumlah_anak" value="<?= $data['jumlah_anak']; ?>" required><br><br>

        <label>Metode KB:</label><br>
        <select name="metode_kb">
            <option value="Suntik" <?= ($data['metode_kontrasepsi'] == 'Suntik') ? 'selected' : ''; ?>>Suntik</option>
            <option value="Pil" <?= ($data['metode_kontrasepsi'] == 'Pil') ? 'selected' : ''; ?>>Pil</option>
            <option value="IUD" <?= ($data['metode_kontrasepsi'] == 'IUD') ? 'selected' : ''; ?>>IUD</option>
            <option value="Implan" <?= ($data['metode_kontrasepsi'] == 'Implan') ? 'selected' : ''; ?>>Implan</option>
        </select><br><br>

        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="kader_data.php">Batal</a>
    </form>
</body>
</html>