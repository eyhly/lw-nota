<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;

class Create extends Component
{
    public $coly, $isi, $nama_barang, $total_coly, $status;

    protected $rules = [
        'coly'        => 'required|numeric',
        'isi'         => 'required|numeric',
        'nama_barang' => 'required|string|max:255',
        'total_coly'  => 'required|numeric',
        'status'      => 'required|string|max:100',
    ];

    public function store()
    {
        $this->validate();

        SuratJalan::create([
            'coly'        => $this->coly,
            'isi'         => $this->isi,
            'nama_barang' => $this->nama_barang,
            'total_coly'  => $this->total_coly,
            'status'      => $this->status,
        ]);

        session()->flash('success', 'Data surat jalan berhasil ditambahkan');
        return redirect()->route('suratjalan.index');
    }

    public function render()
    {
        return view('livewire.suratjalan.create', [
            'title' => 'Tambah Surat Jalan'
        ]);
    }
}
