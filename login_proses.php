<?php
session_start();
// ... koneksi database ...

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user'");
    $data = mysqli_fetch_assoc($result);

    if ($data && password_verify($pass, $data['password'])) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role']; // Menyimpan role (admin/penyuluh/kader)

        // Redirect sesuai role
        header("Location: dashboard_" . $data['role'] . ".php");
    } else {
        echo "Login Gagal!";
    }
}
?>