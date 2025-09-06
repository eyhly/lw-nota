<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use App\Models\DetailSuratJalan;
use Livewire\Component;
use PDF;

class Detail extends Component
{
    public $suratjalan;
    public $title = 'Detail Surat Jalan';    

    public $no_surat, $pembeli, $alamat, $tanggal;

    public $editIndex = null;
    public $editData = [];

    public function mount($id)
    {
        $this->suratjalan = SuratJalan::with('detailsj')->findOrFail($id);

        $this->no_surat     = $this->suratjalan->no_surat;
        $this->pembeli     = $this->suratjalan->pembeli;
        $this->alamat     = $this->suratjalan->alamat;
        $this->tanggal     = $this->suratjalan->tanggal;        
    }

    public function updateSurat()
    {
        $this->validate([
            'no_surat'     => 'required|string',
            'pembeli'     => 'required|string',
            'alamat'     => 'required|string',
            'tanggal'     => 'required|date',                      
        ]);

        $this->suratjalan->update([
            'no_surat'     => $this->no_surat,
            'pembeli'     => $this->pembeli,
            'alamat'     => $this->alamat,
            'tanggal'     => $this->tanggal,            
        ]);

        session()->flash('success', 'Data surat jalan berhasil diperbarui.');
    }

    public function startEdit($index, $detailId)
    {
        $this->editIndex = $index;
        $detail = $this->suratjalan->detailsj->find($detailId);

        $this->editData = [
            'id'          => $detail->id,
            'coly'        => $detail->coly,
            'satuan_coly' => $detail->satuan_coly,
            'isi'         => $detail->isi,
            'nama_isi'    => $detail->nama_isi,
            'nama_barang' => $detail->nama_barang,
        ];
    }

    public function updatedEditData($value, $field)
    {
        // Kalau coly berubah → langsung hitung ulang total coly
        if ($field === 'coly') {
            $this->editData['coly'] = (int) $value;
        }
    }

    public function saveEdit()
    {
        $detail = DetailSuratJalan::find($this->editData['id']);
        $detail->update($this->editData);

        // refresh data
        $this->suratjalan = SuratJalan::with('detailsj')->find($this->suratjalan->id);

        $this->editIndex = null;
        $this->editData = [];
    }

    public function cancelEdit()
    {
        $this->editIndex = null;
        $this->editData = [];
    }

    public function deleteDetail($id)
    {
        DetailSuratJalan::find($id)->delete();
        $this->suratjalan = SuratJalan::with('detailsj')->find($this->suratjalan->id);
    }

    public function getTotalColyProperty()
    {
        return $this->suratjalan->detailsj->sum('coly');
    }

    
    public function pdf($id){
        $suratjalan = SuratJalan::with('detailsj')->findOrFail($id);

        $chunks = $suratjalan->detailsj->chunk(10);

        $data = array(
            'title' => 'Detail SuratJalan',
            'suratjalan' => $suratjalan,
            'chunks' => $chunks
        );
        $pdf = Pdf::loadView('pdf.surat', $data)->setPaper('a4', 'landscape');;
        return $pdf->stream('suratjalan.pdf');
    }

    public function render()
    {
        return view('livewire.suratjalan.detail')->layout('layouts.app');
    }
}
