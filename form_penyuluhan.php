<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Penyuluhan KB</title>
</head>
<body>
    <h2>Formulir Penyuluhan Keluarga Berencana</h2>
    <form action="proses.php" method="POST">
        <label>Nama Lengkap:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>NIK:</label><br>
        <input type="number" name="nik" required><br><br>

        <label>Pilihan Metode KB:</label><br>
        <select name="metode_kb">
            <option value="Suntik">Suntik</option>
            <option value="Pil">Pil</option>
            <option value="IUD">IUD</option>
            <option value="Implan">Implan</option>
        </select><br><br>

        <button type="submit" name="submit">Kirim Data</button>
    </form>
</body>
</html>