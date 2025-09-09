<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiObatModel extends Model
{
    protected $table            = 'log_distribusiobat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_penerima',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'tanggal_distribusi',
        'keterangan',
        'petugas',
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
    protected $validationRules = [
        'nama_penerima'     => 'required|min_length[1]|max_length[255]',
        'kode_barang'       => 'required|max_length[50]',
        'nama_barang'       => 'required|max_length[255]',
        'jumlah'            => 'required|integer|greater_than[0]',
        'tanggal_distribusi'=> 'required|valid_date',
        'keterangan'        => 'permit_empty|string',
        'petugas'           => 'required|min_length[3]|max_length[255]',
    ];
    protected $validationMessages = [
        'nama_penerima' => [
            'required'   => 'Nama penerima wajib diisi.',
            'min_length' => 'Nama penerima minimal 3 karakter.',
        ],
        'kode_barang' => [
            'required'   => 'Kode barang wajib diisi.',
        ],
        'nama_barang' => [
            'required'   => 'Nama barang wajib diisi.',
        ],
        'jumlah' => [
            'required'      => 'Jumlah wajib diisi.',
            'integer'       => 'Jumlah harus berupa angka.',
            'greater_than'  => 'Jumlah minimal 1.',
        ],
        'tanggal_distribusi' => [
            'required'   => 'Tanggal distribusi wajib diisi.',
            'valid_date' => 'Tanggal distribusi tidak valid.',
        ],
        'petugas' => [
            'required'   => 'Nama petugas wajib diisi.',
            'min_length' => 'Nama petugas minimal 3 karakter.',
        ],
    ];
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

    public function getById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function createDistribusi(array $data, string $petugas)
    {
        $obatModel = new \App\Models\ObatModel();
        $obat      = $obatModel->getByKode($data['kode_barang']);
        if (!$obat) {
            $this->errors = ['kode_barang' => 'Kode barang tidak ditemukan.'];
            return false;
        }

        $row = [
            'nama_penerima'      => $data['nama_penerima'],
            'kode_barang'        => $data['kode_barang'],
            'nama_barang'        => $obat->nama_barang,
            'jumlah'             => (int) $data['jumlah'],
            'tanggal_distribusi' => $data['tanggal_distribusi'],
            'keterangan'         => $data['keterangan'] ?? null,
            'petugas'            => $petugas,
            'created_at'         => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();

        if ($this->insert($row) === false) {
            $this->db->transRollback();
            return false;
        }

        // Tambah distribusi -> delta positif
        if ($this->adjustObatStock($data['kode_barang'], +$row['jumlah']) === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->getInsertID();
    }

    public function updateDistribusi(int $id, array $data, string $petugas)
    {
        $current = $this->getById($id);
        if (!$current) {
            $this->errors = ['id' => 'Data distribusi tidak ditemukan.'];
            return false;
        }

        $obatModel = new \App\Models\ObatModel();

        $obatBaru = $obatModel->getByKode($data['kode_barang']);
        if (!$obatBaru) {
            $this->errors = ['kode_barang' => 'Kode barang tidak ditemukan.'];
            return false;
        }

        $updateRow = [
            'nama_penerima'      => $data['nama_penerima'],
            'kode_barang'        => $data['kode_barang'],
            'nama_barang'        => $obatBaru->nama_barang,
            'jumlah'             => (int) $data['jumlah'],
            'tanggal_distribusi' => $data['tanggal_distribusi'] ?? $current->tanggal_distribusi,
            'keterangan'         => $data['keterangan'] ?? $current->keterangan,
            'petugas'            => $petugas,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();

        // Update log
        if ($this->update($id, $updateRow) === false) {
            $this->db->transRollback();
            return false;
        }

        // Sinkron stok:
        if ($current->kode_barang === $updateRow['kode_barang']) {
            // Barang sama -> pakai delta jumlah
            $delta = (int) $updateRow['jumlah'] - (int) $current->jumlah;
            if ($delta !== 0 && $this->adjustObatStock($updateRow['kode_barang'], $delta) === false) {
                $this->db->transRollback();
                return false;
            }
        } else {
            // Kode barang berubah:
            // 1) kembalikan stok lama
            if ($this->adjustObatStock($current->kode_barang, -(int) $current->jumlah) === false) {
                $this->db->transRollback();
                return false;
            }
            // 2) kurangi stok barang baru
            if ($this->adjustObatStock($updateRow['kode_barang'], +(int) $updateRow['jumlah']) === false) {
                $this->db->transRollback();
                return false;
            }
        }

        $this->db->transComplete();
        return true;
    }

    public function deleteDistribusi(int $id)
    {
        $row = $this->getById($id);
        if (!$row) {
            $this->errors = ['id' => 'Data distribusi tidak ditemukan.'];
            return false;
        }

        $this->db->transStart();

        if ($this->delete($id) === false) {
            $this->db->transRollback();
            return false;
        }

        // Hapus distribusi -> stok kembali (delta negatif)
        if ($this->adjustObatStock($row->kode_barang, -(int) $row->jumlah) === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return true;
    }

    private function adjustObatStock(string $kodeBarang, int $delta)
    {
        $obatModel = new \App\Models\ObatModel();
        $obat      = $obatModel->getByKode($kodeBarang);
        if (!$obat) {
            $this->errors = ['kode_barang' => 'Kode barang Obat tidak ditemukan saat sinkron stok.'];
            return false;
        }

        // Hitung berbasis angka dasar untuk akurasi
        $didistribusiBaru = (int) $obat->didistribusi + $delta;
        if ($didistribusiBaru < 0) {
            $didistribusiBaru = 0; // guard
        }

        // sisa = jumlah awal - didistribusi
        $sisaBaru = (int) $obat->jumlah - $didistribusiBaru;
        if ($sisaBaru < 0) {
            $sisaBaru = 0; // guard, jangan minus
        }

        return $obatModel->updateByKode($kodeBarang, [
            'didistribusi' => $didistribusiBaru,
            'sisa'         => $sisaBaru,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
    }
}
