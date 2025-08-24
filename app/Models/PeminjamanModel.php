<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table            = 'log_peminjaman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_peminjam',
        'nama_barang',
        'kode_barang',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'petugas',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAllWithBarang()
    {
        return $this->select('log_peminjaman.*, bmn.nama_barang')
            ->join('barang bmn', 'bmn.kode_barang = log_peminjaman.kode_barang', 'left')
            ->orderBy('log_peminjaman.id', 'ASC')
            ->findAll();
    }

    public function getById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function createPeminjaman($data, $petugas)
    {
        $barangModel = new BarangModel();
        $barang      = $barangModel->getByKode($data['kode_barang']);

        $this->insert([
            'nama_peminjam'  => $data['nama_peminjam'],
            'nama_barang'    => $barang['nama_barang'],
            'kode_barang'    => $data['kode_barang'],
            'jumlah'         => $data['jumlah'],
            'tanggal_pinjam' => $data['tanggal_pinjam'],
            'tanggal_kembali'=> $data['tanggal_kembali'] ?? null,
            'status'         => 'Dipinjam',
            'petugas'        => $petugas,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        $this->updateBarangStock($data['kode_barang']);
    }

    public function updatePeminjaman($id, $data, $petugas)
    {
        $barangModel = new BarangModel();
        $barang      = $barangModel->getByKode($data['kode_barang']);

        $this->update($id, [
            'nama_peminjam'  => $data['nama_peminjam'],
            'nama_barang'    => $barang['nama_barang'],
            'kode_barang'    => $data['kode_barang'],
            'jumlah'         => $data['jumlah'],
            'tanggal_pinjam' => $data['tanggal_pinjam'],
            'tanggal_kembali'=> $data['tanggal_kembali'],
            'status'         => $data['status'],
            'petugas'        => $petugas,
            'updated_at'     => date('Y-m-d H:i:s')
        ]);

        $this->updateBarangStock($data['kode_barang']);
    }

    public function deletePeminjaman($id)
    {
        $peminjaman = $this->getById($id);
        if ($peminjaman) {
            $this->delete($id);
            $this->updateBarangStock($peminjaman->kode_barang);
        }
    }

    public function setKembali($id, $petugas)
    {
        $peminjaman = $this->getById($id);
        if ($peminjaman && $peminjaman->status !== 'Kembali') {
            $this->update($id, [
                'status'          => 'Kembali',
                'tanggal_kembali' => date('Y-m-d'),
                'petugas'         => $petugas,
                'updated_at'      => date('Y-m-d H:i:s')
            ]);
            $this->updateBarangStock($peminjaman->kode_barang);
        }
    }

    private function updateBarangStock($kode_barang)
    {
        $barangModel = new BarangModel();
        $barang      = $barangModel->getByKode($kode_barang);
        if (!$barang) return;

        $dipinjam = $this->selectSum('jumlah')
            ->where('kode_barang', $kode_barang)
            ->where('status', 'Dipinjam')
            ->get()->getRow()->jumlah ?? 0;

        $barangModel->updateByKode($kode_barang, [
            'dipinjam'   => $dipinjam,
            'sisa'       => $barang['jumlah'] - $dipinjam,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
