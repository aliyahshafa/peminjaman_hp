<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;

class PetugasLaporanController extends BaseController
{
    protected $peminjamanModel;

    public function __construct()
    {
        // Memanggil model peminjaman
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo   = $this->request->getGet('date_to');
        $status   = $this->request->getGet('status');

        $db = \Config\Database::connect();
        $builder = $db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left')
            ->orderBy('peminjaman.id_peminjaman', 'DESC');

        if ($dateFrom) {
            $builder->where('DATE(peminjaman.waktu) >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('DATE(peminjaman.waktu) <=', $dateTo);
        }
        if ($status) {
            $builder->where('peminjaman.status', $status);
        }

        $data['peminjaman'] = $builder->get()->getResultArray();
        $data['date_from']  = $dateFrom;
        $data['date_to']    = $dateTo;
        $data['status']     = $status;

        return view('petugas/laporan/index', $data);
    }

    public function cetak()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo   = $this->request->getGet('date_to');
        $status   = $this->request->getGet('status');
        $id       = $this->request->getGet('id');

        $db = \Config\Database::connect();
        $builder = $db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left')
            ->orderBy('peminjaman.id_peminjaman', 'DESC');

        if ($id) {
            $builder->where('peminjaman.id_peminjaman', $id);
        } else {
            if ($dateFrom) {
                $builder->where('DATE(peminjaman.waktu) >=', $dateFrom);
            }
            if ($dateTo) {
                $builder->where('DATE(peminjaman.waktu) <=', $dateTo);
            }
            if ($status) {
                $builder->where('peminjaman.status', $status);
            }
        }

        $data['peminjaman'] = $builder->get()->getResultArray();
        $data['date_from']  = $dateFrom;
        $data['date_to']    = $dateTo;

        return view('petugas/laporan/cetak', $data);
    }
}