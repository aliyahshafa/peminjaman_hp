<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // ambil role user dari session
        $role = session()->get('role');

        // $arguments berisi role yang diizinkan
        if (!in_array($role, $arguments)) {
            // kalau role tidak sesuai, kembalikan ke dashboard masing-masing
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak dipakai
    }
}