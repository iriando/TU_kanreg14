<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class DistribusiObat extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // List semua distribusi
    public function index()
    {
        $rows = $this->db->table('log_distribusiobat lo')
            ->select('lo.*, o.nama_barang AS obat_nama, o.satuan')
            ->join('obat o', 'o.kode_barang = lo.kode_barang', 'left')
            ->orderBy('lo.id', 'ASC')
            ->get()->getResult();

        return view('distribusiobat/index', ['distribusi' => $rows]);
    }

    // Form create
    public function create()
    {
        $obat = $this->db->table('obat')->orderBy('nama_barang','ASC')->get()->getResult();
        return view('distribusiobat/create', ['obat' => $obat]);
    }

    // Simpan
    public function store()
    {
        $rules = [
            'kode_barang'        => 'required',
            'jumlah'             => 'required|integer|greater_than_equal_to[1]',
            'nama_penerima'      => 'required',
            'tanggal_distribusi' => 'required|valid_date',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kode = $this->request->getPost('kode_barang');
        $obat = $this->db->table('obat')->where('kode_barang', $kode)->get()->getRow();
        if (! $obat) {
            return redirect()->back()->withInput()->with('errors', ['kode_barang'=>'Obat tidak ditemukan']);
        }

        $this->db->table('log_distribusiobat')->insert([
            'kode_barang'        => $kode,
            'nama_barang'        => $obat->nama_barang,
            'jumlah'             => (int) $this->request->getPost('jumlah'),
            'nama_penerima'      => $this->request->getPost('nama_penerima'),
            'tanggal_distribusi' => $this->request->getPost('tanggal_distribusi'),
            'petugas'            => user()->username,
            'keterangan'         => $this->request->getPost('keterangan'),
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        $this->recalcObat($kode);

        return redirect()->to('/distribusiobat')->with('message', 'Distribusi obat berhasil ditambahkan');
    }

    // Form edit
    public function edit($id)
    {
        $row = $this->db->table('log_distribusiobat')->where('id', $id)->get()->getRow();
        if (! $row) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data distribusi tidak ditemukan');
        }
        $obat = $this->db->table('obat')->orderBy('nama_barang','ASC')->get()->getResult();

        return view('distribusiobat/edit', [
            'distribusi' => $row,
            'obat'       => $obat,
        ]);
    }

    // Update
    public function update($id)
    {
        $rules = [
            'kode_barang'        => 'required',
            'jumlah'             => 'required|integer|greater_than_equal_to[1]',
            'nama_penerima'      => 'required',
            'tanggal_distribusi' => 'required|valid_date',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kode = $this->request->getPost('kode_barang');
        $obat = $this->db->table('obat')->where('kode_barang', $kode)->get()->getRow();
        if (! $obat) {
            return redirect()->back()->withInput()->with('errors', ['kode_barang'=>'Obat tidak ditemukan']);
        }

        $this->db->table('log_distribusiobat')->where('id', $id)->update([
            'kode_barang'        => $kode,
            'nama_barang'        => $obat->nama_barang,
            'jumlah'             => (int) $this->request->getPost('jumlah'),
            'nama_penerima'      => $this->request->getPost('nama_penerima'),
            'tanggal_distribusi' => $this->request->getPost('tanggal_distribusi'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'updated_at'         => date('Y-m-d H:i:s'),
            'petugas'            => user()->username,
        ]);

        $this->recalcObat($kode);

        return redirect()->to('/distribusiobat')->with('message', 'Distribusi obat berhasil diupdate');
    }

    // Hapus
    public function delete($id)
    {
        $row = $this->db->table('log_distribusiobat')->where('id', $id)->get()->getRow();
        if ($row) {
            $this->db->table('log_distribusiobat')->where('id', $id)->delete();
            $this->recalcObat($row->kode_barang);
        }
        return redirect()->to('/distribusiobat')->with('message', 'Distribusi obat berhasil dihapus');
    }

    /**
     * Hitung ulang didistribusi & sisa pada tabel obat.
     */
    private function recalcObat(string $kodeBarang): void
    {
        $obat = $this->db->table('obat')->where('kode_barang', $kodeBarang)->get()->getRow();
        if (! $obat) return;

        $didistribusi = (int) ($this->db->table('log_distribusiobat')
            ->selectSum('jumlah')
            ->where('kode_barang', $kodeBarang)
            ->get()->getRow()->jumlah ?? 0);

        $sisa = (int) $obat->jumlah - $didistribusi;

        $this->db->table('obat')->where('kode_barang', $kodeBarang)->update([
            'didistribusi' => $didistribusi,
            'sisa'         => $sisa,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
    }
}
