<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribusiObatModel;
use App\Models\LogObatModel;
use App\Models\ObatModel;

class DistribusiObat extends BaseController
{
    protected $distribusiobatModel;
    protected $logObatModel;
    protected $obatModel;

    public function __construct()
    {
        $this->distribusiobatModel = new DistribusiObatModel();
        $this->logObatModel        = new LogObatModel();
        $this->obatModel           = new ObatModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $builder1 = $db->table('log_distribusiobat l')
            ->select("l.id, l.kode_barang, o.nama_barang, o.satuan, l.jumlah, l.nama_penerima, l.tanggal_distribusi AS tanggal, l.petugas, l.keterangan, 'Distribusi' as jenis")
            ->join('obat o', 'o.kode_barang = l.kode_barang', 'left');
        $sql1 = $builder1->getCompiledSelect();
        $builder2 = $db->table('log_obat lo')
            ->select("lo.id, lo.kode_barang, lo.nama_barang, o.satuan, lo.jumlah, '' AS nama_penerima, lo.created_at AS tanggal, lo.petugas, lo.keterangan, 'Log Obat' as jenis")
            ->join('obat o', 'o.kode_barang = lo.kode_barang', 'left');
        $sql2 = $builder2->getCompiledSelect();
        $sql = "({$sql1}) UNION ALL ({$sql2}) ORDER BY tanggal DESC";
        $rows = $db->query($sql)->getResult();
        return view('distribusiobat/index', ['distribusi' => $rows]);
    }

    public function create()
    {
        $obat = $this->obatModel->orderBy('nama_barang','ASC')->findAll();
        return view('distribusiobat/create', ['obat' => $obat]);
    }

    public function store()
    {
        $rules = [
            'kode_barang'        => 'required',
            'jumlah'             => 'required',
            'nama_penerima'      => 'required',
            'tanggal_distribusi' => 'required|valid_date',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kodeBarangArr = $this->request->getPost('kode_barang'); 
        $jumlahArr     = $this->request->getPost('jumlah');      
        $namaPenerima  = $this->request->getPost('nama_penerima');
        $tanggal       = $this->request->getPost('tanggal_distribusi');
        $keterangan    = $this->request->getPost('keterangan');
        $petugas       = user()->username;

        foreach ($kodeBarangArr as $i => $kode) {
            $jumlah = (int) ($jumlahArr[$i] ?? 0);
            if ($jumlah < 1) continue;

            $obat = $this->obatModel->where('kode_barang', $kode)->first();
            if (! $obat) {
                return redirect()->back()->withInput()->with('errors', ['kode_barang'=>'Obat tidak ditemukan']);
            }

            $this->distribusiobatModel->insert([
                'kode_barang'        => $kode,
                'nama_barang'        => $obat->nama_barang,
                'jumlah'             => $jumlah,
                'nama_penerima'      => $namaPenerima,
                'tanggal_distribusi' => $tanggal,
                'petugas'            => $petugas,
                'keterangan'         => $keterangan,
                'created_at'         => date('Y-m-d H:i:s'),
            ]);

            $this->recalcObat($kode);
        }

        return redirect()->to('/distribusiobat')->with('message', 'Distribusi obat berhasil ditambahkan');
    }

    // Form edit
    public function edit($id)
    {
        $row = $this->distribusiobatModel->find($id);
        if (! $row) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data distribusi tidak ditemukan');
        }
        $obat = $this->obatModel->orderBy('nama_barang','ASC')->findAll();

        return view('distribusiobat/edit', [
            'distribusi' => $row,
            'obat'       => $obat,
        ]);
    }

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
        $obat = $this->obatModel->where('kode_barang', $kode)->first();
        if (! $obat) {
            return redirect()->back()->withInput()->with('errors', ['kode_barang'=>'Obat tidak ditemukan']);
        }

        $this->distribusiobatModel->update($id, [
            'kode_barang'        => $kode,
            'nama_barang'        => $obat['nama_barang'],
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

    public function delete($id)
    {
        $row = $this->distribusiobatModel->find($id);
        if ($row) {
            $this->distribusiobatModel->delete($id);
            $this->recalcObat($row->kode_barang);
        }
        return redirect()->to('/distribusiobat')->with('message', 'Distribusi obat berhasil dihapus');
    }

    private function recalcObat(string $kodeBarang): void
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
