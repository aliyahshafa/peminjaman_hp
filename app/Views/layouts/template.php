<!DOCTYPE html>
<!-- Deklarasi tipe dokumen HTML5 -->
<html lang="id">
<!-- Tag html dengan bahasa Indonesia -->
<head>
    <!-- Meta tag untuk charset UTF-8 -->
    <meta charset="UTF-8">
    <!-- Meta tag untuk responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title halaman, jika tidak ada gunakan default -->
    <title><?= $title ?? 'Sistem Peminjaman Alat' ?></title>
    
    <!-- CSS -->
    <!-- Link ke file CSS utama dengan parameter time() untuk cache busting -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">
    
    <!-- Font Awesome -->
    <!-- Link ke CDN Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php 
    // Render section styles jika ada CSS tambahan dari halaman child
    echo $this->renderSection('styles'); 
    ?>
</head>
<body>
    <!-- Wrapper utama untuk layout -->
    <div class="wrapper">
        <!-- Sidebar -->
        <!-- Sidebar navigasi dengan ID untuk JavaScript -->
        <aside class="sidebar" id="sidebar">
            <!-- Header sidebar -->
            <div class="sidebar-header">
                <!-- Logo dan nama aplikasi (SPA = Sistem Peminjaman Alat) -->
                <h3><i class="fas fa-box"></i> <span>SPA</span></h3>
                <!-- Tombol toggle sidebar untuk mobile -->
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <!-- Menu sidebar -->
            <ul class="sidebar-menu">
                <?php 
                // Ambil role user dari session
                $role = session()->get('role');
                // Ambil URL saat ini untuk menandai menu aktif
                $currentUrl = uri_string();
                ?>
                
                <!-- Dashboard -->
                <!-- Menu dashboard yang selalu ada untuk semua role -->
                <li>
                    <!-- Link ke dashboard dengan class active jika URL mengandung 'dashboard' -->
                    <a href="<?= base_url('/dashboard') ?>" class="<?= strpos($currentUrl, 'dashboard') !== false ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <?php 
                // Jika role adalah Admin, tampilkan menu admin
                if ($role == 'Admin'): 
                ?>
                    <!-- Admin Menu -->
                    <!-- Menu kelola user -->
                    <li>
                        <a href="<?= base_url('/admin/user') ?>" class="<?= strpos($currentUrl, 'user') !== false ? 'active' : '' ?>">
                            <i class="fas fa-users"></i>
                            <span>Kelola User</span>
                        </a>
                    </li>
                    <!-- Menu kelola alat -->
                    <li>
                        <a href="<?= base_url('/admin/alat') ?>" class="<?= strpos($currentUrl, 'alat') !== false ? 'active' : '' ?>">
                            <i class="fas fa-box"></i>
                            <span>Kelola Alat</span>
                        </a>
                    </li>
                    <!-- Menu peminjaman -->
                    <li>
                        <a href="<?= base_url('/admin/peminjaman') ?>" class="<?= strpos($currentUrl, 'peminjaman') !== false ? 'active' : '' ?>">
                            <i class="fas fa-handshake"></i>
                            <span>Peminjaman</span>
                        </a>
                    </li>
                    <!-- Menu pengembalian -->
                    <li>
                        <a href="<?= base_url('/admin/pengembalian') ?>" class="<?= strpos($currentUrl, 'pengembalian') !== false ? 'active' : '' ?>">
                            <i class="fas fa-undo"></i>
                            <span>Pengembalian</span>
                        </a>
                    </li>
                    <!-- Menu log aktivitas -->
                    <li>
                        <a href="<?= base_url('/admin/log') ?>" class="<?= strpos($currentUrl, 'log') !== false ? 'active' : '' ?>">
                            <i class="fas fa-history"></i>
                            <span>Log Aktivitas</span>
                        </a>
                    </li>
                    <!-- Menu data terhapus (trash) -->
                    <li>
                        <a href="<?= base_url('/admin/trash') ?>" class="<?= strpos($currentUrl, 'trash') !== false ? 'active' : '' ?>">
                            <i class="fas fa-trash"></i>
                            <span>Data Terhapus</span>
                        </a>
                    </li>
                    
                <?php 
                // Jika role adalah Petugas, tampilkan menu petugas
                elseif ($role == 'Petugas'): 
                ?>
                    <!-- Petugas Menu -->
                    <!-- Menu data alat -->
                    <li>
                        <a href="<?= base_url('/petugas/alat') ?>" class="<?= strpos($currentUrl, 'alat') !== false ? 'active' : '' ?>">
                            <i class="fas fa-box"></i>
                            <span>Data Alat</span>
                        </a>
                    </li>
                    <!-- Menu peminjaman -->
                    <li>
                        <a href="<?= base_url('/petugas/peminjaman') ?>" class="<?= strpos($currentUrl, 'peminjaman') !== false ? 'active' : '' ?>">
                            <i class="fas fa-handshake"></i>
                            <span>Peminjaman</span>
                        </a>
                    </li>
                    <!-- Menu pengembalian -->
                    <li>
                        <a href="<?= base_url('/petugas/pengembalian') ?>" class="<?= strpos($currentUrl, 'pengembalian') !== false ? 'active' : '' ?>">
                            <i class="fas fa-undo"></i>
                            <span>Pengembalian</span>
                        </a>
                    </li>
                    <!-- Menu laporan -->
                    <li>
                        <a href="<?= base_url('/petugas/laporan') ?>" class="<?= strpos($currentUrl, 'laporan') !== false ? 'active' : '' ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    
                <?php 
                // Jika role lainnya (Peminjam), tampilkan menu peminjam
                else: 
                ?>
                    <!-- Peminjam Menu -->
                    <!-- Menu daftar alat -->
                    <li>
                        <a href="<?= base_url('/peminjam/alat') ?>" class="<?= strpos($currentUrl, 'alat') !== false ? 'active' : '' ?>">
                            <i class="fas fa-box"></i>
                            <span>Daftar Alat</span>
                        </a>
                    </li>
                    <!-- Menu peminjaman saya -->
                    <li>
                        <a href="<?= base_url('/peminjam/peminjaman') ?>" class="<?= strpos($currentUrl, 'peminjaman') !== false ? 'active' : '' ?>">
                            <i class="fas fa-handshake"></i>
                            <span>Peminjaman Saya</span>
                        </a>
                    </li>
                    <!-- Menu pengembalian -->
                    <li>
                        <a href="<?= base_url('/peminjam/pengembalian') ?>" class="<?= strpos($currentUrl, 'pengembalian') !== false ? 'active' : '' ?>">
                            <i class="fas fa-undo"></i>
                            <span>Pengembalian</span>
                        </a>
                    </li>
                    <!-- Menu pembayaran denda -->
                    <li>
                        <a href="<?= base_url('/peminjam/pembayaran') ?>" class="<?= strpos($currentUrl, 'pembayaran') !== false ? 'active' : '' ?>">
                            <i class="fas fa-money-bill"></i>
                            <span>Pembayaran Denda</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- Profile & Logout -->
                <!-- Menu profil yang selalu ada untuk semua role -->
                <li>
                    <a href="<?= base_url('/profile') ?>" class="<?= strpos($currentUrl, 'profile') !== false ? 'active' : '' ?>">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>
                <!-- Menu logout yang selalu ada untuk semua role -->
                <li>
                    <a href="<?= base_url('/logout') ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <!-- Konten utama dengan ID untuk JavaScript -->
        <main class="main-content" id="mainContent">
            <!-- Topbar -->
            <!-- Bar atas halaman -->
            <div class="topbar">
                <!-- Bagian kiri topbar -->
                <div class="topbar-left">
                    <!-- Tombol toggle sidebar untuk mobile, hanya tampil di layar kecil -->
                    <button class="btn btn-secondary btn-sm d-md-none" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <!-- Judul halaman, jika tidak ada gunakan default Dashboard -->
                    <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
                </div>
                <!-- Bagian kanan topbar -->
                <div class="topbar-right">
                    <!-- Informasi user yang sedang login -->
                    <div class="user-info">
                        <!-- Avatar user dengan inisial huruf pertama nama -->
                        <div class="user-avatar">
                            <?php 
                            // Ambil huruf pertama dari nama user, ubah ke uppercase
                            echo strtoupper(substr(session()->get('nama_user'), 0, 1)); 
                            ?>
                        </div>
                        <!-- Detail user -->
                        <div class="user-details">
                            <!-- Nama user dari session -->
                            <span class="user-name"><?= session()->get('nama_user') ?></span>
                            <!-- Role user dari session -->
                            <span class="user-role"><?= session()->get('role') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            <!-- Container untuk konten halaman -->
            <div class="container">
                <?php 
                // Cek apakah ada flash message success
                if (session()->getFlashdata('success')): 
                ?>
                    <!-- Alert success dengan icon check-circle -->
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php 
                        // Tampilkan pesan success dari flashdata
                        echo session()->getFlashdata('success'); 
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Cek apakah ada flash message error
                if (session()->getFlashdata('error')): 
                ?>
                    <!-- Alert error dengan icon exclamation-circle -->
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                        // Tampilkan pesan error dari flashdata
                        echo session()->getFlashdata('error'); 
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Render section content dari halaman child
                echo $this->renderSection('content'); 
                ?>
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <!-- JavaScript untuk interaksi halaman -->
    <script>
        // Fungsi untuk toggle (buka/tutup) sidebar
        function toggleSidebar() {
            // Ambil elemen sidebar berdasarkan ID
            const sidebar = document.getElementById('sidebar');
            // Ambil elemen main content berdasarkan ID
            const mainContent = document.getElementById('mainContent');
            
            // Cek lebar layar, jika <= 768px (mobile)
            if (window.innerWidth <= 768) {
                // Toggle class 'show' untuk menampilkan/menyembunyikan sidebar di mobile
                sidebar.classList.toggle('show');
            } else {
                // Untuk desktop, toggle class 'collapsed' untuk minimize sidebar
                sidebar.classList.toggle('collapsed');
                // Toggle class 'expanded' untuk melebarkan konten utama
                mainContent.classList.toggle('expanded');
            }
        }
        
        // Event listener untuk menutup sidebar saat klik di luar sidebar (mobile)
        document.addEventListener('click', function(event) {
            // Ambil elemen sidebar
            const sidebar = document.getElementById('sidebar');
            // Cek apakah klik di dalam sidebar
            const isClickInside = sidebar.contains(event.target);
            // Cek apakah klik pada tombol toggle
            const isToggleButton = event.target.closest('.sidebar-toggle') || event.target.closest('.btn-secondary');
            
            // Jika klik di luar sidebar, bukan tombol toggle, dan layar mobile
            if (!isClickInside && !isToggleButton && window.innerWidth <= 768) {
                // Tutup sidebar dengan menghapus class 'show'
                sidebar.classList.remove('show');
            }
        });
        
        // Auto-hide alerts setelah 5 detik
        setTimeout(function() {
            // Ambil semua elemen alert
            const alerts = document.querySelectorAll('.alert');
            // Loop setiap alert
            alerts.forEach(alert => {
                // Set transisi opacity untuk efek fade out
                alert.style.transition = 'opacity 0.5s';
                // Set opacity menjadi 0 (transparan)
                alert.style.opacity = '0';
                // Setelah 500ms, hapus elemen alert dari DOM
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000); // Jalankan setelah 5000ms (5 detik)
    </script>
    
    <?php 
    // Render section scripts jika ada JavaScript tambahan dari halaman child
    echo $this->renderSection('scripts'); 
    ?>
</body>
</html>
