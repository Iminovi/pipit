<?php
session_start();
// Jika sudah login, langsung lempar ke dashboard masing-masing
if (isset($_SESSION['role'])) {
    header("Location: dashboard_" . $_SESSION['role'] . ".php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi KB</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0px 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 25px; }
        .input-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 16px; }
        button { width: 100%; padding: 12px; background: #3498db; border: none; border-radius: 6px; color: white; font-size: 16px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        .error-msg { background: #ff7675; color: white; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Sistem Informasi <br>Penyuluhan KB</h2>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
        <div class="error-msg">Username atau Password Salah!</div>
    <?php endif; ?>

    <form action="cek_login.php" method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required autofocus>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit" name="login">Masuk ke Sistem</button>
    </form>
    
    <p style="text-align: center; font-size: 12px; color: #999; margin-top: 20px;">
        &copy; 2026 Aplikasi Penyuluhan KB - PHP Native
    </p>
</div>

</body>
</html>