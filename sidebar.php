<!-- Sidebar untuk semua halaman -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>SIREKAP KB</h4>
    </div>
    <div class="nav flex-column mt-3">
        <a href="dashboard_admin.php" class="nav-link active">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>
        <a href="admin_users.php" class="nav-link">
            <span class="material-symbols-outlined">people</span>
            Kelola Pengguna
        </a>
        <a href="user_tambah.php" class="nav-link">
            <span class="material-symbols-outlined">person_add</span>
            Tambah Pengguna
        </a>
        <a href="penyuluh_laporan.php" class="nav-link">
            <span class="material-symbols-outlined">description</span>
            Laporan
        </a>
        <a href="dashboard_grafik.php" class="nav-link">
            <span class="material-symbols-outlined">bar_chart</span>
            Statistik
        </a>
        <a href="admin_rekap_honor.php" class="nav-link">
            <span class="material-symbols-outlined">receipt</span>
            Rekap Honor
        </a>
        <div class="section-header">PROFIL</div>
        <a href="profil.php" class="nav-link">
            <span class="material-symbols-outlined">account_circle</span>
            Profil Saya
        </a>
        <a href="logout.php" class="nav-link text-danger">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>
    </div>
</div>

<!-- Top Navbar dengan Hamburger -->
<div class="top-navbar">
    <button class="hamburger-btn" id="hamburgerBtn">
        <span class="material-symbols-outlined">menu</span>
    </button>
    <div class="navbar-brand ms-2">
        <h5 class="mb-0">Sistem KB</h5>
    </div>
    <div class="ms-auto">
        <span class="me-3">User: <?= $_SESSION['nama_lengkap'] ?? 'Admin' ?></span>
        <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; font-size: 0.9rem; margin: 0; padding: 0; }
    
    .sidebar { 
        width: 260px; 
        height: 100vh; 
        background: #fff; 
        border-right: 1px solid #dee2e6; 
        position: fixed; 
        overflow-y: auto; 
        z-index: 1000; 
        transition: transform 0.3s ease;
        top: 0;
        left: 0;
    }
    
    .sidebar-header { 
        padding: 20px; 
        border-bottom: 1px solid #f1f1f1; 
        text-align: center; 
    }
    
    .sidebar-header h4 { 
        margin: 0; 
        font-size: 18px; 
        color: #0d6efd;
        font-weight: 700;
    }
    
    .nav { 
        list-style: none; 
        padding: 0; 
        margin: 0;
    }
    
    .nav-link { 
        color: #333; 
        display: flex; 
        align-items: center; 
        padding: 12px 20px; 
        border-left: 4px solid transparent; 
        transition: 0.3s; 
        text-decoration: none;
    }
    
    .nav-link:hover, .nav-link.active { 
        background: #eef3ff; 
        color: #0d6efd; 
        border-left-color: #0d6efd; 
    }
    
    .nav-link.text-danger:hover { 
        background: #ffe0e0; 
        color: #dc3545;
        border-left-color: #dc3545;
    }
    
    .nav-link span { 
        margin-right: 10px; 
        font-size: 20px; 
    }
    
    .section-header { 
        color: #999; 
        font-size: 12px; 
        font-weight: 600; 
        text-transform: uppercase; 
        padding: 15px 20px 5px; 
        margin-top: 10px;
    }
    
    .top-navbar { 
        background: #fff; 
        padding: 12px 30px; 
        border-bottom: 1px solid #dee2e6; 
        margin-left: 260px;
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        transition: margin-left 0.3s ease;
    }
    
    .hamburger-btn { 
        display: none; 
        background: #0d6efd; 
        color: white; 
        border: none; 
        padding: 8px 12px; 
        border-radius: 6px; 
        cursor: pointer; 
        font-size: 18px;
    }
    
    .hamburger-btn:hover { 
        background: #0b5ed7; 
    }
    
    .main-content { 
        margin-left: 260px; 
        padding: 20px; 
        transition: margin-left 0.3s ease;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar { 
            transform: translateX(-100%); 
            width: 100%; 
            max-width: 260px;
        }
        
        .sidebar.active { 
            transform: translateX(0); 
        }
        
        .top-navbar {
            margin-left: 0;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .main-content { 
            margin-left: 0; 
            padding: 15px; 
        }
        
        .hamburger-btn { 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .navbar-brand { 
            font-size: 14px; 
        }
    }
    
    @media (max-width: 480px) {
        .top-navbar { 
            padding: 10px 15px; 
            font-size: 0.85rem;
        }
        
        .navbar-brand h5 {
            font-size: 16px;
        }
        
        .main-content {
            padding: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }
        
        // Tutup sidebar saat klik di main-content
        if (mainContent) {
            mainContent.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            });
        }
    });
</script>