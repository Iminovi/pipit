<?php
session_start();
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $user = $_POST['username'];
    $nama = $_POST['nama_lengkap'];
    $role = $_POST['role'];
    // Enkripsi password
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, nama_lengkap, role) 
            VALUES ('$user', '$pass', '$nama', '$role')";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: admin_users.php");
    } else {
        echo "Gagal tambah user: " . mysqli_error($koneksi);
    }
}
?>

<form method="POST">
    <h3>Tambah Akun Baru</h3>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required><br><br>
    <select name="role">
        <option value="penyuluh">Penyuluh KB</option>
        <option value="kader">Kader KB</option>
    </select><br><br>
    <button type="submit" name="simpan">Simpan User</button>
</form>