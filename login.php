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
        body, html { 
            height: 100%; 
            margin: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f5f5f5;
        }
        
        .login-container { 
            display: flex; 
            height: 100vh; 
            width: 100%; 
            background: white;
        }

        /* Sisi Kiri: Gambar Background */
        .login-image {
            flex: 1.2;
            background: linear-gradient(135deg, #1a5da4 0%, #0d3d7d 100%);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,170.7C384,181,480,171,576,160C672,149,768,139,864,144C960,149,1056,171,1152,176C1248,181,1344,171,1392,165.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
        }

        .login-image-content {
            position: relative;
            z-index: 1;
        }

        .login-image-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .login-image h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-image p {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Sisi Kanan: Form Login */
        .login-form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background-color: white;
        }

        .login-box { 
            width: 100%; 
            max-width: 420px; 
        }
        
        .login-box-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-bkkbn { 
            width: 90px; 
            height: 90px;
            margin-bottom: 15px;
            object-fit: contain;
        }

        .login-box-header h5 {
            font-size: 18px;
            font-weight: 700;
            color: #1a5da4;
            margin-bottom: 5px;
        }

        .login-box-header p {
            font-size: 13px;
            color: #666;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-control {
            padding: 11px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a5da4;
            box-shadow: 0 0 0 0.2rem rgba(26, 93, 164, 0.15);
            outline: none;
        }

        .form-check {
            margin-top: 15px;
        }

        .form-check-label {
            font-size: 13px;
            margin-left: 5px;
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

        @media (min-width: 992px) {
            .login-image { 
                display: flex;
            }
        }

        @media (max-width: 991px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-form-section {
                flex: 1;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-image">
        <div class="login-image-content">
            <div class="login-image-logo">
                <span class="material-symbols-outlined" style="font-size: 60px; color: #1a5da4;">family_restroom</span>
            </div>
            <h3>SIREKAP MKJP</h3>
            <p>Sistem Informasi Keluarga</p>
            <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">Kementerian Kependudukan dan Pembangunan Keluarga</p>
        </div>
    </div>

    <div class="login-form-section">
        <div class="login-box">
            <div class="login-box-header">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b3/Logo_BKKBN.png" alt="Logo BKKBN" class="logo-bkkbn">
                <h5>SIREKAP MKJP</h5>
                <p>Sistem informasi registrasi kader pembawa aseptor metode kontrasepsi jangka panjang/p>
                <p style="font-size: 12px; color: #999;">Silakan masuk ke akun Anda</p>
            </div>

            <?php if(!empty($error)): ?>
                <div class="alert-error">
                    <span class="material-symbols-outlined" style="font-size: 20px;">error</span>
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">
                        <span class="material-symbols-outlined" style="font-size: 18px;">account_circle</span>
                        ID Pengguna
                    </label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan ID Pengguna" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <span class="material-symbols-outlined" style="font-size: 18px;">lock</span>
                        Kata Sandi
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Kata Sandi" required>
                </div>

                <div class="form-check small">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label text-muted" for="remember">Ingatkan Saya</label>
                </div>

                <button type="submit" name="login" class="btn-primary-custom">
                    <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                    MASUK
                </button>
            </form>

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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>