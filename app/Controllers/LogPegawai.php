<?php

namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\LogPegawaiModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LogPegawai extends BaseController
{
    protected $logModel;
    protected $pegawaiModel;

    public function __construct()
    {
        $this->logModel = new LogPegawaiModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $pegawaiId = $this->request->getGet('pegawai_id');
        $tanggal   = $this->request->getGet('tanggal');

        $builder = $this->logModel
            ->select('log_pegawai.*, pegawai.nama')
            ->join('pegawai', 'pegawai.id = log_pegawai.pegawai_id');

        if ($pegawaiId) {
            $builder->where('log_pegawai.pegawai_id', $pegawaiId);
        }

        if ($tanggal) {
            $builder->where('log_pegawai.tanggal', $tanggal);
        }

        $logs = $builder->orderBy('log_pegawai.tanggal', 'DESC')->findAll();

        $tanggal   = $this->request->getGet('tanggal');

        return view('logpegawai/index', [
            'logs'    => $logs,
            'pegawai' => $this->pegawaiModel->findAll(),
            'tanggal' => $tanggal,
            'pegawaiId' => $pegawaiId,
        ]);
    }

    public function logBaru()
    {
        $tanggal = $this->request->getGet('tanggal');

        if (!$tanggal) {
            return redirect()->to(base_url('log_pegawai'));
        }

        // ambil semua pegawai
        $pegawai = $this->pegawaiModel->findAll();

        // === CEK hanya insert log baru kalau BELUM ADA sama sekali untuk tanggal itu ===
        $cekTanggal = $this->logModel->where('tanggal', $tanggal)->countAllResults();

        if ($cekTanggal == 0) {
            foreach ($pegawai as $p) {
                $this->logModel->insert([
                    'pegawai_id' => $p->id,
                    'tanggal'    => $tanggal,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // ambil data log (sudah ada / baru dibuat)
        $logs = $this->logModel
            ->select('log_pegawai.*, pegawai.nama')
            ->join('pegawai', 'pegawai.id = log_pegawai.pegawai_id')
            ->where('tanggal', $tanggal)
            ->orderBy('pegawai.nama', 'ASC')
            ->findAll();

        return view('logpegawai/logbaru', [
            'tanggal' => $tanggal,
            'logs'    => $logs,
        ]);
    }

    public function updatePerPegawai($id)
    {
        $tanggal = $this->request->getPost('tanggal'); // ambil tanggal log

        $data = [
            'waktu_masuk'   => $this->combineDateTime($tanggal, $this->request->getPost('waktu_masuk')),
            'waktu_keluar'  => $this->combineDateTime($tanggal, $this->request->getPost('waktu_keluar')),
            'waktu_kembali' => $this->combineDateTime($tanggal, $this->request->getPost('waktu_kembali')),
            'waktu_pulang'  => $this->combineDateTime($tanggal, $this->request->getPost('waktu_pulang')),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $this->logModel->update($id, $data);

        return redirect()->back()->with('success', 'Log berhasil diperbarui');
    }

    /**
     * Gabungkan tanggal + jam menjadi format DATETIME
     */
    private function combineDateTime($tanggal, $waktu)
    {
        if (!$waktu) return null; // jika kosong, jangan simpan apa2
        return $tanggal . ' ' . $waktu . ':00'; // format yyyy-mm-dd HH:MM:SS
    }

}
