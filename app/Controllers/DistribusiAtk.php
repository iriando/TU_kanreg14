<?php

namespace App\Controllers;

use Config\Database;
use App\Models\AtkModel;
use App\Models\LogAtkModel;
use App\Models\DistribusiAtkModel;
use App\Controllers\BaseController;

class DistribusiAtk extends BaseController
{
    protected $distribusiAtkModel;
    protected $logAtkModel;
    protected $atkModel;

    public function __construct()
    {
        $this->distribusiAtkModel = new DistribusiAtkModel();
        $this->logAtkModel        = new LogAtkModel();
        $this->atkModel           = new AtkModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $builder1 = $db->table('log_distribusiatk l')
            ->select("l.id, l.kode_barang, a.nama_barang, a.satuan, l.jumlah, l.nama_penerima, l.tanggal_distribusi AS tanggal, l.petugas, l.keterangan, 'Distribusi' as jenis")
            ->join('atk a', 'a.kode_barang = l.kode_barang', 'left');
        $sql1 = $builder1->getCompiledSelect();
        $builder2 = $db->table('log_atk la')
            ->select("la.id, la.kode_barang, la.nama_barang, a.satuan, la.jumlah, '' AS nama_penerima, la.created_at AS tanggal, la.petugas, la.keterangan, 'Log Atk' as jenis")
            ->join('atk a', 'a.kode_barang = la.kode_barang', 'left');
        $sql2 = $builder2->getCompiledSelect();
        $sql = "({$sql1}) UNION ALL ({$sql2}) ORDER BY tanggal DESC";
        $rows = $db->query($sql)->getResult();
        // dd($rows);
        // die;
        return view('distribusiatk/index', ['distribusi' => $rows]);
    }

    public function create()
    {
        $atk = $this->atkModel->orderBy('nama_barang','ASC')->findAll();
        return view('distribusiatk/create', ['atk' => $atk]);
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

            $atk = $this->atkModel->where('kode_barang', $kode)->first();
            if (! $atk) {
                return redirect()->back()->withInput()->with('errors', ['kode_barang'=>'ATK / ATRK tidak ditemukan']);
            }

            $this->distribusiAtkModel->insert([
                'kode_barang'        => $kode,
                'nama_barang'        => $atk->nama_barang,
                'jumlah'             => $jumlah,
                'nama_penerima'      => $namaPenerima,
                'tanggal_distribusi' => $tanggal,
                'petugas'            => $petugas,
                'keterangan'         => $keterangan,
                'created_at'         => date('Y-m-d H:i:s'),
            ]);

            $this->recalcAtk($kode);
        }

        return redirect()->to('/distribusiatk')->with('message', 'Distribusi ATK / ATRK berhasil ditambahkan');
    }


    public function edit($id)
    {
        $row = $this->distribusiAtkModel->find($id);
        if (! $row) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data distribusi tidak ditemukan');
        }
        $atk = $this->atkModel->orderBy('nama_barang','ASC')->findAll();

        return view('distribusiatk/edit', [
            'distribusi' => $row,
            'atk'       => $atk,
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
        $atk = $this->atkModel->where('kode_barang', $kode)->first();
        if (! $atk) {
            return redirect()->back()->withInput()->with('errors', ['kode_barang'=>'ATK / ATRK tidak ditemukan']);
        }

        $this->distribusiAtkModel->update($id, [
            'kode_barang'        => $kode,
            'nama_barang'        => $atk->nama_barang,
            'jumlah'             => (int) $this->request->getPost('jumlah'),
            'nama_penerima'      => $this->request->getPost('nama_penerima'),
            'tanggal_distribusi' => $this->request->getPost('tanggal_distribusi'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'updated_at'         => date('Y-m-d H:i:s'),
            'petugas'            => user()->username,
        ]);

        $this->recalcAtk($kode);

        return redirect()->to('/distribusiatk')->with('message', 'Distribusi ATK / ATRK berhasil diupdate');
    }

    public function delete($id)
    {
        $row = $this->distribusiAtkModel->find($id);
        if ($row) {
            $this->distribusiAtkModel->delete($id);
            $this->recalcAtk($row->kode_barang);
        }
        return redirect()->to('/distribusiatk')->with('message', 'Distribusi ATK / ATRK berhasil dihapus');
    }

    private function recalcAtk(string $kodeBarang): void
    {
        $atk = $this->atkModel->where('kode_barang', $kodeBarang)->first();
        if (! $atk) return;

        $didistribusi = (int) $this->distribusiAtkModel
            ->selectSum('jumlah')
            ->where('kode_barang', $kodeBarang)
            ->first()->jumlah ?? 0;

        $sisa = (int) $atk->jumlah - $didistribusi;

        $this->atkModel->where('kode_barang', $kodeBarang)->set([
            'didistribusi' => $didistribusi,
            'sisa'         => $sisa,
            'updated_at'   => date('Y-m-d H:i:s'),
        ])->update();
    }
}
