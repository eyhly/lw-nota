<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Create extends Component
{
    public $no_surat, $pembeli, $tanggal, $kendaraan, $no_kendaraan, $coly, $isi, $nama_barang, $total_coly, $status;
    public $detailsj = [];
    public $title = 'Tambah Surat Jalan';

    public $formDetail = [
        'coly'        => 0,
        'isi'         => '',
        'nama_barang' => '',
    ];

    public function mount()
    {
        $this->resetForm();
        // otomatis generate nomor baru
        $this->no_surat = SuratJalan::generateNextNoSuratJalan();
        $this->tanggal = now()->toDateString();
        $this->status = 'Belum';
    }

    public function resetForm()
    {
        $this->reset([
            'pembeli',
            'tanggal',
            'total_coly',
            'status',
            'detailsj',
        ]);
    }

        public function addDetail()
    {
        $this->detailsj[] = $this->formDetail;

        $this->formDetail = [
            'coly'        => 0,
            'isi'         => '',
            'nama_barang' => '',
        ];
    }

    public function removeDetail($index)
    {
        unset($this->detailsj[$index]);
        $this->detailsj = array_values($this->detailsj);
    }

    public function store()
    {
        $this->validate([
            'no_surat' => 'required|unique:surat_jalan,no_surat',
            'tanggal' => 'required|date',
            'pembeli' => 'required',
        ]);

        DB::transaction(function() {
            $suratjalan = SuratJalan::create([
                'no_surat' => $this->no_surat,
                'pembeli' => $this->pembeli,
                'tanggal' => $this->tanggal,
                'kendaraan' => $this->kendaraan,
                'no_kendaraan' => $this->no_kendaraan,
                'total_coly' => $this->total_coly,
                'status' => $this->status,
            ]);

            foreach($this->detailsj as $d) {
                $suratjalan->detailsj()->create($d);
            }
        });

        session()->flash('success', 'Data surat jalan berhasil ditambahkan');
        return redirect()->route('suratjalan.index');
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
