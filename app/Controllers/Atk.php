<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AtkModel;
use App\Models\DistribusiAtkModel;
use App\Models\LogAtkModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Atk extends BaseController
{
    protected $atkModel;
    protected $distribusiAtkModel;
    protected $logAtkModel;

    public function __construct()
    {
        $this->atkModel         = new AtkModel();
        $this->distribusiAtkModel = new DistribusiAtkModel();
        $this->logAtkModel      = new LogAtkModel();
    }

    public function index()
    {
        $data['atk'] = $this->atkModel->findAll();
        return view('admin/atk/index', $data);
    }

    public function create()
    {
        return view('admin/atk/create');
    }

    public function store()
    {
        $data = [
            'kode_barang'  => $this->request->getPost('kode_barang'),
            'nama_barang'  => $this->request->getPost('nama_barang'),
            'satuan'       => $this->request->getPost('satuan'),
            'jumlah'       => (int) $this->request->getPost('jumlah'),
            'didistribusi' => 0,
            'sisa'         => (int) $this->request->getPost('jumlah'),
        ];

        // validasi
        $this->atkModel->setValidationRules($this->atkModel->getValidationRules());

        if (! $this->atkModel->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->atkModel->errors());
        }

        $petugas = user()->username ?? 'system';

        // log stok awal ke log_obat (bukan distribusi)
        $this->logAtkModel->insert([
            'kode_barang' => $data['kode_barang'],
            'nama_barang' => $data['nama_barang'],
            'jumlah'      => $data['jumlah'],
            'aksi'        => 'Tambah stok',
            'keterangan'  => 'Stok awal',
            'petugas'     => $petugas,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/atk')->with('message', 'ATK / ATRK berhasil ditambahkan');
    }

    public function edit($id)
    {
        $atk = $this->atkModel->find($id);
        if (! $atk) {
            throw new PageNotFoundException("ATK / ATRK dengan ID $id tidak ditemukan");
        }
        return view('admin/atk/edit', ['atk' => $atk]);
    }

    public function update($id)
    {
        $atkLama = $this->atkModel->find($id);
        if (! $atkLama) {
            return redirect()->to('/atk')->with('error', 'Data ATK / ATRK tidak ditemukan');
        }

        $jumlahBaru = (int) $this->request->getPost('jumlah');
        $jumlahLama = (int) $atkLama->jumlah;
        $selisih    = $jumlahBaru - $jumlahLama;

        $data = [
            'id'          => $id,
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'      => $this->request->getPost('satuan'),
            'jumlah'      => $jumlahBaru,
            'didistribusi'=> (int) $atkLama->didistribusi,
            'sisa'        => max(0, $jumlahBaru - (int) $atkLama->didistribusi),
        ];

        try {
            $this->atkModel->save($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode barang sudah digunakan!');
        }

        $petugas = user()->username ?? 'system';

        if ($selisih > 0) {
            // log penambahan stok
            $this->logAtkModel->insert([
                'kode_barang' => $data['kode_barang'],
                'nama_barang' => $data['nama_barang'],
                'jumlah'      => $selisih,
                'aksi'        => 'Tambah stok',
                'keterangan'  => 'Update ATK / ATRK (penambahan)',
                'petugas'     => $petugas,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        if ($selisih < 0) {
            // log pengurangan stok
            $this->logAtkModel->insert([
                'kode_barang' => $data['kode_barang'],
                'nama_barang' => $data['nama_barang'],
                'jumlah'      => abs($selisih),
                'aksi'        => 'Koreksi stok',
                'keterangan'  => 'Update obat (pengurangan)',
                'petugas'     => $petugas,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/atk')->with('message', 'atk berhasil diupdate');
    }

    public function delete($id)
    {
        $atk = $this->atkModel->find($id);

        if ($atk) {
            $petugas = user()->username ?? 'system';

            // log hapus stok
            $this->logAtkModel->insert([
                'kode_barang' => $atk->kode_barang,
                'nama_barang' => $atk->nama_barang,
                'jumlah'      => $atk->jumlah,
                'aksi'        => 'Hapus ATK / ATRK',
                'keterangan'  => 'ATK / ATRK dihapus dari sistem',
                'petugas'     => $petugas,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            $this->atkModel->delete($id);
        }

        return redirect()->to('/atk')->with('message', 'ATK / ATRK berhasil dihapus');
    }
}
