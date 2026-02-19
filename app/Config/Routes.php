<?php

// Import RouteCollection dari CodeIgniter Router
use CodeIgniter\Router\RouteCollection;

/**
 * File konfigurasi routing aplikasi
 * Mendefinisikan semua URL dan controller yang menanganinya
 * 
 * @var RouteCollection $routes
 */

// ===============================================
// ROUTES AUTENTIKASI (LOGIN & LOGOUT)
// ===============================================

// Route untuk halaman utama (redirect ke login)
// GET / -> AuthController::login
$routes->get('/', 'AuthController::login');

// Route untuk proses login (submit form)
// POST /login -> AuthController::process
$routes->post('login', 'AuthController::process');

// Route untuk logout
// GET /logout -> AuthController::logout
$routes->get('logout', 'AuthController::logout');

// ===============================================
// DASHBOARD REDIRECT
// ===============================================

// Route untuk dashboard (dengan filter auth untuk cek login)
// GET /dashboard -> DashboardController::index
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);


// ===============================================
// ROUTES UNTUK ADMIN
// Semua route di group ini hanya bisa diakses oleh Admin
// ===============================================
$routes->group('admin', ['filter' => 'role:Admin'], function ($routes) {

    // Dashboard Admin
    // GET /admin -> AdminController::index
    $routes->get('/', 'AdminController::index');

    // ==================
    // MANAJEMEN USER
    // ==================
    
    // Daftar user
    // GET /admin/user -> UserController::index
    $routes->get('user', 'UserController::index');
    
    // Form tambah user
    // GET /admin/user/create -> UserController::create
    $routes->get('user/create', 'UserController::create');
    
    // Simpan user baru
    // POST /admin/user/store -> UserController::store
    $routes->post('user/store', 'UserController::store');
    
    // Form edit user
    // GET /admin/user/edit/{id} -> UserController::edit
    $routes->get('user/edit/(:num)', 'UserController::edit/$1');
    
    // Update user
    // POST /admin/user/update/{id} -> UserController::update
    $routes->post('user/update/(:num)', 'UserController::update/$1');
    
    // Hapus user
    // GET /admin/user/delete/{id} -> UserController::delete
    $routes->get('user/delete/(:num)', 'UserController::delete/$1');

    // ==================
    // MANAJEMEN ALAT/HP
    // ==================
    
    // Daftar alat
    // GET /admin/alat -> AlatController::index
    $routes->get('alat', 'AlatController::index');
    
    // Form tambah alat
    // GET /admin/alat/create -> AlatController::create
    $routes->get('alat/create', 'AlatController::create');
    
    // Simpan alat baru
    // POST /admin/alat/store -> AlatController::store
    $routes->post('alat/store', 'AlatController::store');
    
    // Form edit alat
    // GET /admin/alat/edit/{id} -> AlatController::edit
    $routes->get('alat/edit/(:num)', 'AlatController::edit/$1');
    
    // Update alat
    // POST /admin/alat/update/{id} -> AlatController::update
    $routes->post('alat/update/(:num)', 'AlatController::update/$1');
    
    // Hapus alat
    // GET /admin/alat/delete/{id} -> AlatController::delete
    $routes->get('alat/delete/(:num)', 'AlatController::delete/$1');

    // ==================
    // TRASH SYSTEM (RECYCLE BIN)
    // ==================
    
    // Daftar data di trash
    // GET /admin/trash -> TrashController::index
    $routes->get('trash', 'TrashController::index');
    
    // Restore data dari trash
    // GET /admin/trash/restore/{id} -> TrashController::restore
    $routes->get('trash/restore/(:num)', 'TrashController::restore/$1');
    
    // Hapus permanen dari trash
    // GET /admin/trash/permanent-delete/{id} -> TrashController::permanentDelete
    $routes->get('trash/permanent-delete/(:num)', 'TrashController::permanentDelete/$1');
    
    // Kosongkan trash
    // GET /admin/trash/clear -> TrashController::clear
    $routes->get('trash/clear', 'TrashController::clear');

    // ==================
    // MANAJEMEN PEMINJAMAN (ADMIN)
    // ==================
    
    // Daftar peminjaman
    // GET /admin/peminjaman -> PeminjamanController::index
    $routes->get('peminjaman', 'PeminjamanController::index');
    
    // Form edit peminjaman
    // GET /admin/peminjaman/edit/{id} -> PeminjamanController::edit
    $routes->get('peminjaman/edit/(:num)', 'PeminjamanController::edit/$1');
    
    // Update peminjaman
    // POST /admin/peminjaman/update/{id} -> PeminjamanController::update
    $routes->post('peminjaman/update/(:num)', 'PeminjamanController::update/$1');
    
    // Hapus/arsipkan peminjaman
    // GET /admin/peminjaman/delete/{id} -> PeminjamanController::delete
    $routes->get('peminjaman/delete/(:num)', 'PeminjamanController::delete/$1');

    // ==================
    // PENGEMBALIAN (ADMIN)
    // ==================
    
    // Daftar pengembalian
    // GET /admin/pengembalian -> AdminPeminjamController::pengembalian
    $routes->get('pengembalian', 'AdminPeminjamController::pengembalian');
    
    // Setujui pengembalian
    // GET /admin/pengembalian/setujui/{id} -> AdminPeminjamController::setujuiPengembalian
    $routes->get('pengembalian/setujui/(:num)','AdminPeminjamController::setujuiPengembalian/$1');

    // ==================
    // LOG AKTIVITAS
    // ==================
    
    // Daftar log aktivitas
    // GET /admin/log -> LogController::index
    $routes->get('log', 'LogController::index');
});


// ===============================================
// ROUTES UNTUK PETUGAS
// Semua route di group ini hanya bisa diakses oleh Petugas
// ===============================================
$routes->group('petugas', ['filter' => 'role:Petugas'], function ($routes) {

    // Dashboard Petugas
    // GET /petugas -> PetugasController::index
    $routes->get('/', 'PetugasController::index');

    // ==================
    // MANAJEMEN PEMINJAMAN (PETUGAS)
    // ==================
    
    // Daftar peminjaman yang perlu diverifikasi
    // GET /petugas/peminjaman -> PetugasPeminjamanController::index
    $routes->get('peminjaman', 'PetugasPeminjamanController::index');
    
    // Detail peminjaman
    // GET /petugas/peminjaman/detail/{id} -> PetugasPeminjamanController::detail
    $routes->get('peminjaman/detail/(:num)', 'PetugasPeminjamanController::detail/$1');
    
    // Form edit peminjaman
    // GET /petugas/peminjaman/edit/{id} -> PetugasPeminjamanController::edit
    $routes->get('peminjaman/edit/(:num)', 'PetugasPeminjamanController::edit/$1');
    
    // Update peminjaman
    // POST /petugas/peminjaman/update/{id} -> PetugasPeminjamanController::update
    $routes->post('peminjaman/update/(:num)', 'PetugasPeminjamanController::update/$1');
    
    // Update peminjaman (route alternatif)
    // POST /petugas/petugas/peminjaman/update{id} -> PetugasPeminjamanController::update
    $routes->post('petugas/peminjaman/update(:num)', 'PetugasPeminjamanController::update/$1');
    
    // ==================
    // MONITORING PENGEMBALIAN (PETUGAS)
    // ==================
    
    // Daftar pengembalian
    // GET /petugas/pengembalian -> PetugasPengembalianController::index
    $routes->get('pengembalian', 'PetugasPengembalianController::index');
    
    // Proses pengembalian
    // POST /petugas/pengembalian/store -> PetugasPengembalianController::store
    $routes->post('pengembalian/store', 'PetugasPengembalianController::store');

    // ==================
    // LAPORAN (PETUGAS)
    // ==================
    
    // Halaman laporan
    // GET /petugas/laporan -> PetugasLaporanController::index
    $routes->get('laporan', 'PetugasLaporanController::index');
});

// ==================
// DATA HANDPHONE - PETUGAS (ROUTE TERPISAH)
// ==================

// Daftar HP untuk petugas
// GET /petugas/alat -> AlatController::index (dengan filter role Petugas)
$routes->get('petugas/alat', 'AlatController::index', [
    'filter' => 'role:Petugas'
]);


// ===============================================
// ROUTES UNTUK PEMINJAM
// Semua route di group ini hanya bisa diakses oleh Peminjam
// ===============================================
$routes->group('peminjam', ['filter' => 'role:Peminjam'], function ($routes) {

    // ==================
    // DASHBOARD PEMINJAM
    // ==================
    
    // Dashboard peminjam
    // GET /peminjam -> DashboardPeminjam::index
    $routes->get('/', 'DashboardPeminjam::index');
    
    // ==================
    // DAFTAR HP TERSEDIA
    // ==================
    
    // Daftar HP yang bisa dipinjam
    // GET /peminjam/alat -> AlatController::index
    $routes->get('alat', 'AlatController::index');
    
    // ==================
    // PEMINJAMAN
    // ==================
    
    // Form ajukan peminjaman
    // GET /peminjam/peminjaman/create/{id_hp} -> PeminjamanController::create
    $routes->get('peminjaman/create/(:num)', 'PeminjamanController::create/$1');
    
    // Simpan peminjaman baru
    // POST /peminjam/peminjaman/store -> PeminjamanController::store
    $routes->post('peminjaman/store', 'PeminjamanController::store');
    
    // ==================
    // PENGEMBALIAN
    // ==================
    
    // Daftar pengembalian
    // GET /peminjam/pengembalian -> DashboardPeminjam::pengembalian
    $routes->get('pengembalian', 'DashboardPeminjam::pengembalian');
    
    // Daftar peminjaman aktif
    // GET /peminjam/peminjaman -> DashboardPeminjam::peminjaman
    $routes->get('peminjaman', 'DashboardPeminjam::peminjaman');
    
    // Form pengembalian HP
    // GET /peminjam/peminjaman/kembalikan/{id} -> DashboardPeminjam::formKembalikan
    $routes->get('peminjaman/kembalikan/(:num)', 'DashboardPeminjam::formKembalikan/$1');
    
    // Proses pengembalian HP
    // POST /peminjam/peminjaman/kembalikan/{id} -> DashboardPeminjam::kembalikan
    $routes->post('peminjaman/kembalikan/(:num)', 'DashboardPeminjam::kembalikan/$1');
    
    // ==================
    // PEMBAYARAN DENDA
    // ==================
    
    // Daftar pembayaran
    // GET /peminjam/pembayaran -> PembayaranController::index
    $routes->get('pembayaran', 'PembayaranController::index');
    
    // Form pembayaran
    // GET /peminjam/pembayaran/bayar/{id} -> PembayaranController::bayar
    $routes->get('pembayaran/bayar/(:num)', 'PembayaranController::bayar/$1');
    
    // Proses pembayaran
    // POST /peminjam/pembayaran/proses/{id} -> PembayaranController::proses
    $routes->post('pembayaran/proses/(:num)', 'PembayaranController::proses/$1');
    
    // Detail pembayaran
    // GET /peminjam/pembayaran/detail/{id} -> PembayaranController::detail
    $routes->get('pembayaran/detail/(:num)', 'PembayaranController::detail/$1');
});

// ===============================================
// ROUTES PROFILE (SEMUA ROLE)
// Bisa diakses oleh semua user yang sudah login
// ===============================================

// Lihat profil
// GET /profile -> ProfileController::index (dengan filter auth)
$routes->get('profile', 'ProfileController::index', ['filter' => 'auth']);

// Form edit profil
// GET /profile/edit -> ProfileController::edit
$routes->get('profile/edit', 'ProfileController::edit');

// Update profil
// POST /profile/update -> ProfileController::update
$routes->post('profile/update', 'ProfileController::update');
