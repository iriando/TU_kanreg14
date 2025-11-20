<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribusiObatModel;
use App\Models\ObatModel;
use App\Models\PegawaiModel;
use App\Models\RekamMedisDetailModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RekamMedisModel;

class RekamMedis extends BaseController
{
    protected $rekamMedisModel;
    protected $distribusiobatModel;
    protected $pegawaiModel;
    protected $obatModel;

    public function __construct()
    {
        $this->rekamMedisModel = new RekamMedisModel();
        $this->distribusiobatModel = new DistribusiObatModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->obatModel = new ObatModel();
    }

    public function index()
    {
        $data['rekammedis'] = $this->rekamMedisModel->findAll();
        $pegawai = $this->pegawaiModel->findAll();

            foreach ($pegawai as &$p) {
            if (!empty($p->tanggal_lahir)) {
                $dob = new \DateTime($p->tanggal_lahir);
                $today = new \DateTime('today');
                $p->usia = $dob->diff($today)->y;
            } else {
                $p->usia = '-';
            }
        }

        $data['pegawai'] = $pegawai;
        return view('rekammedis/index', $data);
    }

    public function view($idPegawai)
    {
        // Data pasien
        $pegawai = $this->pegawaiModel->find($idPegawai);
        if (!$pegawai) {
            return redirect()->to('/rekammedis')->with('error', 'Pegawai tidak ditemukan');
        }

        // Hitung usia
        if (!empty($pegawai->tanggal_lahir)) {
            $dob = new \DateTime($pegawai->tanggal_lahir);
            $pegawai->usia = $dob->diff(new \DateTime('today'))->y;
        } else {
            $pegawai->usia = '-';
        }

        // Riwayat rekam medis
        $rekam = $this->rekamMedisModel
            ->where('id_pegawai', $idPegawai)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        // Load obat yg terkait via log_distribusiobat
        $logDistribusi = [];
        foreach ($rekam as $r) {
            $logDistribusi[$r->id] = $this->distribusiobatModel
                ->where('rekam_medis_id', $r->id)
                ->findAll();
        }

        return view('rekammedis/view', [
            'pegawai'       => $pegawai,
            'rekam'         => $rekam,
            'logDistribusi' => $logDistribusi
        ]);
    }

    public function create($idPegawai)
    {
        $pegawai = $this->pegawaiModel->find($idPegawai);

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan');
        }

        // hitung usia
        $dob = new \DateTime($pegawai->tanggal_lahir);
        $today = new \DateTime('today');
        $usia = $dob->diff($today)->y;

        // ambil daftar obat
        $obat = $this->obatModel
                    ->select('kode_barang, nama_barang, sisa')
                    ->findAll();

        return view('rekammedis/create', [
            'pegawai' => $pegawai,
            'usia'    => $usia,
            'obat'    => $obat
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        // 1️⃣ Simpan header rekam medis
        $rekamMedisId = $this->rekamMedisModel->insert([
            'id_pegawai'   => $data['id_pegawai'],
            'nama_pasien'  => $data['nama_pasien'],
            'tanggal'      => $data['tanggal'],
            'keluhan'      => $data['keluhan'],
            'petugas'      => user()->username,
            'keterangan'   => $data['keterangan'],
        ]);

        // 2️⃣ Validasi & Simpan Distribusi Obat
        if (!empty($data['kode_barang'])) {

            foreach ($data['kode_barang'] as $i => $kode) {

                if ($kode == "" || $data['jumlah'][$i] == "") {
                    continue;
                }

                // Ambil data obat
                $obat = $this->obatModel->where('kode_barang', $kode)->first();
                if (!$obat) continue;

                $jumlahRequest = (int) $data['jumlah'][$i];

                // ❌ VALIDASI STOK HABIS
                if ($obat->sisa <= 0) {
                    return redirect()->back()
                        ->with('error', "Stok obat <b>{$obat->nama_barang}</b> sudah <b>HABIS</b>!")
                        ->withInput();
                }

                // ❌ VALIDASI STOK TIDAK CUKUP
                if ($jumlahRequest > $obat->sisa) {
                    return redirect()->back()
                        ->with('error', "Stok obat <b>{$obat->nama_barang}</b> tidak cukup! Stok tersisa: <b>{$obat->sisa}</b>")
                        ->withInput();
                }

                // ✔️ Simpan distribusi obat
                $this->distribusiobatModel->insert([
                    'rekam_medis_id'    => $rekamMedisId,
                    'kode_barang'       => $kode,
                    'nama_barang'       => $obat->nama_barang,
                    'jumlah'            => $jumlahRequest,
                    'nama_penerima'     => $data['nama_pasien'],
                    'tanggal_distribusi'=> $data['tanggal'],
                    'petugas'           => user()->username,
                    'keterangan'        => 'Distribusi via rekam medis',
                    'created_at'        => date('Y-m-d H:i:s'),
                ]);

                // 🔄 Recalc stok obat
                $this->recalcObat($kode);
            }
        }

        return redirect()->to('/rekammedis')
            ->with('success', 'Rekam medis berhasil disimpan!');
    }


    // 🔄 fungsi recalcObat sama seperti di controller DistribusiObat
    private function recalcObat($kodeBarang)
    {
        $obat = $this->obatModel->where('kode_barang', $kodeBarang)->first();
        if (! $obat) return;

        $didistribusi = (int) $this->distribusiobatModel
            ->selectSum('jumlah')
            ->where('kode_barang', $kodeBarang)
            ->first()->jumlah ?? 0;

        $sisa = (int) $obat->jumlah - $didistribusi;

        $this->obatModel->where('kode_barang', $kodeBarang)->set([
            'didistribusi' => $didistribusi,
            'sisa'         => $sisa,
            'updated_at'   => date('Y-m-d H:i:s'),
        ])->update();
    }

}
