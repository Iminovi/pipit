<?php
session_start();
include 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } elseif ($user['role'] == 'penyuluh') {
            header("Location: dashboard_penyuluh.php");
        } elseif ($user['role'] == 'kader') {
            header("Location: dashboard_kader.php");
        }
        exit;
    } else {
        $error = "ID Pengguna atau Kata Sandi salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIREKAP MKJP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container { 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); 
            padding: 40px; 
            width: 100%; 
            max-width: 400px; 
        }

        .login-header { 
            text-align: center; 
            margin-bottom: 30px; 
        }

        .login-header h2 { 
            color: #667eea; 
            font-weight: bold; 
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a5da4;
            box-shadow: 0 0 0 0.2rem rgba(26, 93, 164, 0.15);
            outline: none;
        }

        .btn-primary-custom {
            background-color: #1a5da4;
            border: none;
            padding: 11px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            border-radius: 6px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            transition: 0.3s;
        }

        .btn-primary-custom:hover { 
            background-color: #14467a;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(26, 93, 164, 0.3);
        }

        .alert-error {
            background: #fee;
            color: #c33;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .demo-info {
            background: #f0f7ff;
            color: #1a5da4;
            padding: 15px;
            border-radius: 6px;
            font-size: 13px;
            margin-top: 20px;
            border-left: 4px solid #1a5da4;
        }

        .demo-info strong {
            display: block;
            margin-bottom: 8px;
            color: #1a5da4;
        }

        .demo-info p {
            margin: 5px 0;
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
            line-height: 1.5;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }

        .register-link p {
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
        }

        .btn-register-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #28a745;
            color: white;
            padding: 11px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-register-custom:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }

        @media (max-width: 480px) {
            .login-container { 
                padding: 20px; 
                margin: 15px; 
            }
            .login-header h2 { 
                font-size: 24px; 
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-header">
        <h2>SIREKAP MKJP</h2>
        <p class="text-muted">Sistem Informasi Keluarga Berencana</p>
    </div>
    <?php if(!empty($error)): ?>
        <div class="alert-error">
            <span class="material-symbols-outlined" style="font-size: 20px;">error</span>
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" class="form-control" name="username" placeholder="ID Pengguna" required>
        <input type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
        <button type="submit" name="login" class="btn-primary-custom">
            <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
            MASUK
        </button>
    </form>

    <div class="register-link">
        <p>Belum punya akun Kader?</p>
        <a href="register.php" class="btn-register-custom">
            <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
            Daftar Akun Kader
        </a>
    </div>

    <div class="demo-info">
        <strong>🔐 Akun Demo:</strong>
        <p><b>Admin:</b> admin / password</p>
        <p><b>Penyuluh:</b> penyuluh / password</p>
        <p><b>Kader:</b> kader / password</p>
    </div>

    <div class="login-footer">
        <p>Copyright © 2024</p>
        <p>Kementerian Kependudukan dan Pembangunan Keluarga / BKKBN</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>