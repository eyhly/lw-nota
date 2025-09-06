<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use App\Models\DetailNota;
use Livewire\Component;
use PDF;

class Detail extends Component
{
    public $nota;
    public $title = 'Detail Nota'; 
    
    public $no_nota, $pembeli, $tanggal, $alamat, $jt_tempo, 
           $subtotal, $total_harga, $total_coly, 
           $diskon_persen = 0, $diskon_rupiah = 0;

    public $editIndex = null;
    public $editData = [];

    public function mount($id)
    {
        $this->nota = Nota::with('details')->findOrFail($id);

        $this->no_nota   = $this->nota->no_nota;
        $this->pembeli   = $this->nota->pembeli;
        $this->tanggal   = $this->nota->tanggal;
        $this->alamat    = $this->nota->alamat;
        $this->jt_tempo  = $this->nota->jt_tempo;
    }

    public function updateNota()
    {
        $this->validate([
            'no_nota'  => 'required|string',
            'pembeli'  => 'required|string',
            'tanggal'  => 'required|date',
            'alamat'   => 'required|string',
            'jt_tempo' => 'required|date',
        ]);

        $this->nota->update([
            'no_nota'  => $this->no_nota,
            'pembeli'  => $this->pembeli,
            'tanggal'  => $this->tanggal,
            'alamat'   => $this->alamat,
            'jt_tempo' => $this->jt_tempo,
        ]);

        session()->flash('success', 'Data nota berhasil diperbarui.');
    }

    public function startEdit($index, $detailId)
    {
        $this->editIndex = $index;
        $detail = $this->nota->details->find($detailId);

        $this->editData = [
            'id'          => $detail->id,
            'nama_barang' => $detail->nama_barang,
            'coly'        => $detail->coly,
            'satuan_coly' => $detail->satuan_coly,
            'qty_isi'     => $detail->qty_isi,
            'nama_isi'    => $detail->nama_isi,
            'jumlah'      => $detail->jumlah,
            'harga'       => $detail->harga,
            'total_harga' => $detail->total_harga,
            'diskon'      => $detail->diskon,
            'total'       => $detail->total,
        ];
    }

    public function saveEdit()
    {
        $detail = DetailNota::find($this->editData['id']);

        // Hitung ulang jumlah & total sebelum update
        $this->editData['jumlah'] = $this->editData['coly'] * $this->editData['qty_isi'];
        $diskonPersen = floatval($this->editData['diskon']) / 100;
        $this->editData['total'] = ($this->editData['jumlah'] * $this->editData['harga']) * (1 - $diskonPersen);

        $detail->update($this->editData);

        // refresh data
        $this->nota = Nota::with('details')->find($this->nota->id);

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
        DetailNota::find($id)->delete();
        $this->nota = Nota::with('details')->find($this->nota->id);
    }

    public function getSubtotalProperty()
    {
        return $this->nota->details->sum('total');
    }

    public function getTotalHargaProperty()
    {
        return $this->subtotal - ($this->diskon_rupiah ?? 0);
    }

    public function getTotalColyProperty()
    {
        return $this->nota->details->sum('coly');
    }

    public function pdf($id){
        $nota = Nota::with('details')->findOrFail($id);

        $chunks = $nota->details->chunk(10);

        $data = array(
            'title' => 'Detail Nota',
            'nota' => $nota,
            'chunks' => $chunks
        );
        $pdf = Pdf::loadView('pdf.index', $data)->setPaper('a4', 'landscape');;
        return $pdf->stream('nota.pdf');
    }

    public function render()
    {
        $this->subtotal    = $this->getSubtotalProperty();
        $this->total_harga = $this->getTotalHargaProperty();
        $this->total_coly  = $this->getTotalColyProperty();

        return view('livewire.nota.detail')->layout('layouts.app');
    }
}
