<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk encoding dan responsive -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Title halaman -->
    <title>Login - Sistem Peminjaman Alat</title>
    
    <!-- Link ke Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Reset CSS - Menghapus margin dan padding default browser */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Agar padding tidak menambah ukuran element */
        }
        
        /* Style untuk body - Background gradient dan center content */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Font modern */
            background: linear-gradient(135deg, #269bd1 0%, #4ab2e2 100%); /* Gradient biru */
            min-height: 100vh; /* Minimal tinggi 100% viewport */
            display: flex; /* Flexbox untuk centering */
            align-items: center; /* Center vertical */
            justify-content: center; /* Center horizontal */
            padding: 20px; /* Padding untuk mobile */
        }
        
        /* Container utama login - Card dengan 2 kolom */
        .login-container {
            background: white; /* Background putih */
            border-radius: 16px; /* Sudut melengkung */
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); /* Shadow untuk depth */
            overflow: hidden; /* Agar border-radius bekerja pada child */
            max-width: 900px; /* Lebar maksimal */
            width: 100%; /* Lebar 100% sampai max-width */
            display: grid; /* Grid layout */
            grid-template-columns: 1fr 1fr; /* 2 kolom sama besar */
        }
        
        /* Kolom kiri - Panel informasi dengan gradient */
        .login-left {
            background: linear-gradient(135deg,  #269bd1 0%, #4ab2e2 100%); /* Gradient biru */
            padding: 60px 40px; /* Padding dalam */
            color: white; /* Text putih */
            display: flex; /* Flexbox */
            flex-direction: column; /* Susun vertical */
            justify-content: center; /* Center vertical */
        }
        
        /* Heading di kolom kiri */
        .login-left h1 {
            font-size: 32px; /* Ukuran font besar */
            margin-bottom: 20px; /* Jarak ke bawah */
        }
        
        /* Paragraph di kolom kiri */
        .login-left p {
            font-size: 16px; /* Ukuran font */
            line-height: 1.6; /* Jarak antar baris */
            opacity: 0.9; /* Sedikit transparan */
        }
        
        /* Icon besar di kolom kiri */
        .login-left .icon {
            font-size: 80px; /* Icon besar */
            margin-bottom: 30px; /* Jarak ke bawah */
            opacity: 0.8; /* Sedikit transparan */
        }
        
        /* Kolom kanan - Form login */
        .login-right {
            padding: 60px 40px; /* Padding dalam */
        }
        
        /* Header form login */
        .login-header {
            text-align: center; /* Text center */
            margin-bottom: 40px; /* Jarak ke bawah */
        }
        
        /* Heading form login */
        .login-header h2 {
            font-size: 28px; /* Ukuran font */
            color: #1e293b; /* Warna gelap */
            margin-bottom: 10px; /* Jarak ke bawah */
        }
        
        /* Subheading form login */
        .login-header p {
            color: #64748b; /* Warna abu-abu */
            font-size: 14px; /* Ukuran font kecil */
        }
        
        /* Group untuk setiap input field */
        .form-group {
            margin-bottom: 25px; /* Jarak antar field */
        }
        
        /* Label untuk input field */
        .form-label {
            display: block; /* Block element */
            margin-bottom: 8px; /* Jarak ke input */
            font-weight: 500; /* Font semi-bold */
            color: #1e293b; /* Warna gelap */
            font-size: 14px; /* Ukuran font */
        }
        
        /* Input field */
        .form-control {
            width: 100%; /* Lebar penuh */
            padding: 12px 15px; /* Padding dalam */
            padding-left: 45px; /* Extra padding kiri untuk icon */
            border: 2px solid #e2e8f0; /* Border abu-abu */
            border-radius: 8px; /* Sudut melengkung */
            font-size: 14px; /* Ukuran font */
            transition: all 0.3s; /* Animasi smooth */
        }
        
        /* Input field saat focus */
        .form-control:focus {
            outline: none; /* Hapus outline default */
            border-color: #667eea; /* Border biru saat focus */
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); /* Shadow biru */
        }
        
        /* Container untuk input dengan icon */
        .input-group {
            position: relative; /* Untuk positioning icon */
        }
        
        /* Icon di dalam input field */
        .input-group i {
            position: absolute; /* Absolute positioning */
            left: 15px; /* Jarak dari kiri */
            top: 50%; /* Posisi vertical center */
            transform: translateY(-50%); /* Center vertical perfect */
            color: #64748b; /* Warna abu-abu */
        }
        
        /* Tombol login */
        .btn-login {
            width: 100%; /* Lebar penuh */
            padding: 14px; /* Padding dalam */
            background: linear-gradient(135deg, #269bd1 0%, #4ab2e2 100%); /* Gradient biru */
            color: white; /* Text putih */
            border: none; /* Hapus border */
            border-radius: 8px; /* Sudut melengkung */
            font-size: 16px; /* Ukuran font */
            font-weight: 600; /* Font bold */
            cursor: pointer; /* Cursor pointer */
            transition: all 0.3s; /* Animasi smooth */
        }
        
        /* Tombol login saat hover */
        .btn-login:hover {
            transform: translateY(-2px); /* Naik sedikit */
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3); /* Shadow saat hover */
        }
        
        /* Alert box untuk pesan error/success */
        .alert {
            padding: 12px 15px; /* Padding dalam */
            border-radius: 8px; /* Sudut melengkung */
            margin-bottom: 20px; /* Jarak ke bawah */
            display: flex; /* Flexbox */
            align-items: center; /* Center vertical */
            gap: 10px; /* Jarak antar element */
        }
        
        /* Alert danger (error) */
        .alert-danger {
            background: #fee2e2; /* Background merah muda */
            color: #991b1b; /* Text merah gelap */
            border-left: 4px solid #ef4444; /* Border kiri merah */
        }
        
        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            /* Container jadi 1 kolom di mobile */
            .login-container {
                grid-template-columns: 1fr; /* 1 kolom saja */
            }
            
            /* Sembunyikan kolom kiri di mobile */
            .login-left {
                display: none;
            }
            
            /* Kurangi padding di mobile */
            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Container utama login -->
    <div class="login-container">
        <!-- Kolom kiri - Panel informasi -->
        <div class="login-left">
            <!-- Icon besar -->
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <!-- Judul sistem -->
            <h1>Sistem Peminjaman Handphone</h1>
            <!-- Deskripsi sistem -->
            <p>Kelola peminjaman handphone dengan mudah dan efisien. Sistem terintegrasi untuk admin, petugas, dan peminjam.</p>
        </div>
        
        <!-- Kolom kanan - Form login -->
        <div class="login-right">
            <!-- Header form -->
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Silakan login untuk melanjutkan</p>
            </div>
            
            <!-- Tampilkan pesan error jika ada -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <!-- Form login - POST ke /login -->
            <form method="post" action="<?= base_url('login') ?>">
                <!-- CSRF token untuk keamanan -->
                <?= csrf_field() ?>
                
                <!-- Input email -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <!-- Icon email -->
                        <i class="fas fa-envelope"></i>
                        <!-- Input field email -->
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required autofocus>
                    </div>
                </div>
                
                <!-- Input password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <!-- Icon lock -->
                        <i class="fas fa-lock"></i>
                        <!-- Input field password -->
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                
                <!-- Tombol submit login -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>

