<?php  

// Deklarasi namespace
namespace App\Controllers;  

/**
 * Controller Home
 * Controller default untuk halaman utama aplikasi
 */
class Home extends BaseController  
{
    /**
     * Method index - menampilkan halaman dashboard utama
     * 
     * @return string View dashboard
     */
    public function index(): string  
    {
        // Render view dashboard utama
        return view('dashboard/index.php');
    }
}
