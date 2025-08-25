<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;

class Edit extends Component
{
    public $coly, $isi, $nama_barang, $total_coly, $status, $suratjalanId;

    public function mount($id)
    {
        $suratjalan = SuratJalan::findOrFail($id);

        $this->suratjalanId = $suratjalan->id;
        $this->coly = $suratjalan->coly;
        $this->isi = $suratjalan->isi;
        $this->nama_barang = $suratjalan->nama_barang;
        $this->total_coly = $suratjalan->total_coly;
        $this->status = $suratjalan->status;
    }

    public function update()
    {
        $this->validate([
            'coly' => 'required|numeric',
            'isi' => 'required',
            'nama_barang' => 'required|string',
            'total_coly' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $suratjalan = SuratJalan::findOrFail($this->suratjalanId);

        $suratjalan->update([
            'coly' => $this->coly,
            'isi' => $this->isi,
            'nama_barang' => $this->nama_barang,
            'total_coly' => $this->total_coly,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Data surat jalan berhasil diupdate');
        return redirect()->route('suratjalan.index');
    }

    public function render()
    {
        return view('livewire.suratjalan.edit', [
            'title' => 'Edit Surat Jalan'
        ]);
    }
}
