<?php

// ============================================
// ROUTES UNTUK LOG AKTIVITAS
// ============================================
// Tambahkan routes ini ke file app/Config/Routes.php

// Log Routes - Hanya untuk Admin
$routes->group('admin/log', ['filter' => 'role:Admin'], function($routes) {
    $routes->get('/', 'LogController::index');                  // Lihat semua log
    $routes->get('stats', 'LogController::stats');              // Statistik log
    $routes->get('detail/(:num)', 'LogController::detail/$1');  // Detail log (opsional)
    $routes->get('export', 'LogController::export');            // Export log (CSV/PDF)
    $routes->get('clear', 'LogController::clear');              // Form hapus log lama
    $routes->post('clear', 'LogController::clear');             // Proses hapus log lama
});

// Atau jika tidak menggunakan filter role, gunakan ini:
/*
$routes->group('admin', function($routes) {
    $routes->group('log', function($routes) {
        $routes->get('/', 'LogController::index');
        $routes->get('stats', 'LogController::stats');
        $routes->get('detail/(:num)', 'LogController::detail/$1');
        $routes->get('export', 'LogController::export');
        $routes->get('clear', 'LogController::clear');
        $routes->post('clear', 'LogController::clear');
    });
});
*/

// ============================================
// CONTOH LENGKAP ROUTES.PHP
// ============================================
/*
<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

// Dashboard Routes
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

// Admin Routes
$routes->group('admin', ['filter' => 'role:Admin'], function($routes) {
    $routes->get('/', 'AdminController::index');
    
    // Alat Management
    $routes->get('alat', 'AlatController::index');
    $routes->get('alat/create', 'AlatController::create');
    $routes->post('alat/create', 'AlatController::create');
    $routes->get('alat/edit/(:num)', 'AlatController::edit/$1');
    $routes->post('alat/edit/(:num)', 'AlatController::edit/$1');
    $routes->get('alat/delete/(:num)', 'AlatController::delete/$1');
    
    // User Management
    $routes->get('user', 'UserController::index');
    $routes->get('user/create', 'UserController::create');
    $routes->post('user/create', 'UserController::create');
    $routes->get('user/edit/(:num)', 'UserController::edit/$1');
    $routes->post('user/edit/(:num)', 'UserController::edit/$1');
    $routes->get('user/delete/(:num)', 'UserController::delete/$1');
    
    // Peminjaman Management
    $routes->get('peminjaman', 'PeminjamanController::index');
    $routes->get('peminjaman/approve/(:num)', 'PeminjamanController::approve/$1');
    $routes->get('peminjaman/reject/(:num)', 'PeminjamanController::reject/$1');
    $routes->get('peminjaman/return/(:num)', 'PeminjamanController::return/$1');
    
    // Log Management
    $routes->get('log', 'LogController::index');
    $routes->get('log/stats', 'LogController::stats');
    $routes->get('log/export', 'LogController::export');
    $routes->get('log/clear', 'LogController::clear');
    $routes->post('log/clear', 'LogController::clear');
});

// Petugas Routes
$routes->group('petugas', ['filter' => 'role:Petugas'], function($routes) {
    $routes->get('/', 'PetugasController::index');
    
    // Alat (View Only)
    $routes->get('alat', 'PetugasController::alat');
    
    // Peminjaman
    $routes->get('peminjaman', 'PetugasPeminjamanController::index');
    
    // Pengembalian
    $routes->get('pengembalian', 'PetugasPengembalianController::index');
    
    // Laporan
    $routes->get('laporan', 'PetugasLaporanController::index');
    $routes->get('laporan/cetak', 'PetugasLaporanController::cetak');
});

// Peminjam Routes
$routes->group('peminjam', ['filter' => 'role:Peminjam'], function($routes) {
    $routes->get('/', 'PeminjamController::index');
    
    // Alat (View Only)
    $routes->get('alat', 'PeminjamController::alat');
    
    // Peminjaman
    $routes->get('peminjaman', 'PeminjamPeminjamanController::index');
    $routes->get('peminjaman/create', 'PeminjamPeminjamanController::create');
    $routes->post('peminjaman/create', 'PeminjamPeminjamanController::create');
    $routes->get('peminjaman/cancel/(:num)', 'PeminjamPeminjamanController::cancel/$1');
    
    // Pengembalian (View Only)
    $routes->get('pengembalian', 'PeminjamController::pengembalian');
});

// Profile Routes (All Roles)
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('edit', 'ProfileController::edit');
    $routes->post('edit', 'ProfileController::edit');
});
*/
