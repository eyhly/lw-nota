<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Create extends Component
{
    public $no_surat, $nama_toko, $alamat, $tanggal, $coly, $satuan_coly, $qty_isi, $nama_isi, $nama_barang, $keterangan, $total_coly, $status, $nota, $print;
    public $detailsj = [];
    public $title = 'Tambah Surat Jalan';

    // Untuk edit inline
    public $editIndex = null;
    public $editData = [];

    public $formDetail = [
        'coly'        => 0,
        'satuan_coly' => '',
        'qty_isi'     => 0,
        'nama_isi'    => '',
        'nama_barang' => '',
        'keterangan' => '',
    ];

    public function mount()
    {
        $this->resetForm();
        // otomatis generate nomor baru
        $this->no_surat = SuratJalan::generateNextNoSuratJalan();
        $this->tanggal = now()->toDateString();
        $this->status = 1;
    }

    public function resetForm()
    {
        $this->reset([
            'nama_toko',
            'alamat',
            'tanggal',
            'total_coly',
            'status',
            'detailsj',
        ]);
    }

        public function addDetail()
    {
        if (empty($this->formDetail['nama_barang'])) {
            return;
        }

        $this->detailsj[] = $this->formDetail;

        $this->formDetail = [
            'coly'        => 0,
            'satuan_coly' => '',
            'qty_isi'         => 0,
            'nama_isi'    => '',
            'nama_barang' => '',
            'keterangan' => '',
        ];

        $this->dispatch('focus-nama-barang');
    }

    public function removeDetail($index)
    {
        unset($this->detailsj[$index]);
        $this->detailsj = array_values($this->detailsj);
    }

    public function startEdit($index)
    {
        $this->editIndex = $index;
        $this->editData = $this->detailsj[$index];
    }

    public function saveEdit()
    {
        $this->detailsj[$this->editIndex] = $this->editData;

        $this->editIndex = null;
        $this->editData = [];
    }

    public function cancelEdit()
    {
        $this->editIndex = null;
        $this->editData = [];
    }

    public function getFormTotalProperty()
    {
        $coly = (int) ($this->formDetail['coly'] ?? 0);
        $qty_isi  = (int) ($this->formDetail['qty_isi'] ?? 0);

        return $coly * $qty_isi;
    }

    public function store()
    {
        $this->dispatch('swal-loading', [
            'title' => 'Menyimpan Surat Jalan...',
            'message' => 'Mohon tunggu'
        ]);

        $this->validate([
            'no_surat' => 'required|unique:surat_jalan,no_surat',
            'tanggal' => 'required|date',
            'nama_toko' => 'required',
            'alamat' => 'required',
        ]);

        if (empty($this->detailsj)) {
            $this->dispatch('swal-close');
            session()->flash('error', 'Minimal harus ada 1 barang!');
            return;
        }

        DB::transaction(function() {
            $suratjalan = SuratJalan::create([
                'no_surat' => $this->no_surat,
                'nama_toko' => $this->nama_toko,
                'alamat' => $this->alamat,
                'tanggal' => $this->tanggal,
                'total_coly' => $this->total_coly,
                'status' => $this->status,
                'nota' => 0,
                'print' => 0,
            ]);

            foreach($this->detailsj as $d) {
                $suratjalan->detailsj()->create($d);
            }
        });

        $this->dispatch('showSuccessAlert', [
            'message' => 'Surat Jalan berhasil disimpan!',
            'redirect' => route(name: 'suratjalan.index')
        ]);
    }

    public function getTotalColyProperty()
    {
        return collect($this->detailsj)->sum('coly');
    }

    public function render()
    {
        $this->total_coly = $this->getTotalColyProperty();
        return view('livewire.suratjalan.create', [
            'title' => 'Tambah Surat Jalan'
        ]);
    }
}
