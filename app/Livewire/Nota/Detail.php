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
    
    public $no_nota, $pembeli, $tanggal, $nama_toko, $alamat, $jt_tempo, 
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
        $this->nama_toko    = $this->nota->nama_toko;
        $this->alamat    = $this->nota->alamat;
        $this->jt_tempo  = $this->nota->jt_tempo;
        $this->diskon_persen  = $this->nota->diskon_persen ?? 0;
        $this->diskon_rupiah  = $this->nota->diskon_rupiah ?? 0;
        $this->total_harga    = $this->nota->total_harga ?? 0;
    }

    public function updateNota()
    {
        $this->validate([
            'no_nota'  => 'required|string',
            'pembeli'  => 'nullable|string',
            'tanggal'  => 'required|date',
            'nama_toko'   => 'required|string',
            'alamat'   => 'required|string',
            'jt_tempo' => 'required|date',
        ]);

        $this->nota->update([
            'no_nota'  => $this->no_nota,
            'pembeli'  => $this->pembeli,
            'tanggal'  => $this->tanggal,
            'nama_toko'   => $this->nama_toko,
            'alamat'   => $this->alamat,
            'jt_tempo' => $this->jt_tempo,
        ]);

        $this->dispatch('notaUpdated');
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
            'diskon'      => $detail->diskon
                                    ? explode(',', $detail->diskon)
                                    : [],
            'total'       => $detail->total,
        ];

        $this->recalcEditTotal();
    }

    public function saveEdit()
    {
       $detail = DetailNota::find($this->editData['id']);

    // jumlah barang
    $this->editData['jumlah'] =
        (int) $this->editData['coly'] * (int) $this->editData['qty_isi'];

    // total diskon persen
    $diskon = array_sum(
        array_map('floatval', (array) ($this->editData['diskon'] ?? []))
    );


    $this->dispatch('$refresh');
    // subtotal
    $subtotal = $this->editData['jumlah'] * (int) $this->editData['harga'];

    // total setelah diskon
    $this->editData['total'] =
        $subtotal * (1 - ($diskon / 100));

    // simpan diskon ke DB
    $this->editData['diskon'] = implode(', ', array_map(
        'strval',
        $this->editData['diskon'] ?? []
    ));

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

    public function addEditDiskon()
    {
        if (!is_array($this->editData['diskon'])) {
            $this->editData['diskon'] = [];
        }

        $this->editData['diskon'][] = 0;
    }

    public function removeEditDiskon($index)
    {
        unset($this->editData['diskon'][$index]);
        $this->editData['diskon'] = array_values($this->editData['diskon']);
    }

    public function updatedEditData($value, $name)
    {
        if (
            str_contains($name, 'coly') ||
            str_contains($name, 'qty_isi') ||
            str_contains($name, 'harga') ||
            str_contains($name, 'diskon')
        ) {
            $this->recalcEditTotal();
        }
    }

    private function recalcEditTotal()
    {
        if (empty($this->editData)) return;

        $coly  = (int) ($this->editData['coly'] ?? 0);
        $qty   = (int) ($this->editData['qty_isi'] ?? 0);
        $harga = (int) ($this->editData['harga'] ?? 0);

        $diskon = array_sum(
            array_map('floatval', (array) ($this->editData['diskon'] ?? []))
        );

        // guard
        $diskon = min($diskon, 100);

        $jumlah = $coly * $qty;
        $subtotal = $jumlah * $harga;

        $this->editData['jumlah'] = $jumlah;
        $this->editData['total']  = round(
            $subtotal * (1 - ($diskon / 100))
        );
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
        $pdf = Pdf::loadView('pdf.index', $data)->setPaper('a5', 'landscape');;
        return $pdf->stream('nota.pdf');
    }

    public function render()
    {
        $this->subtotal    = $this->getSubtotalProperty();
        $this->total_coly  = $this->getTotalColyProperty();
        $this->total_harga = $this->getTotalHargaProperty();

        return view('livewire.nota.detail')->layout('layouts.app');
    }

    public function updatePrintAndRedirect($id)
    {
        $nota = Nota::findOrFail($id);

        // update print = 1
        $nota->update(['print' => 1]);

        // redirect ke halaman PDF
        return redirect()->route('pdf.index', $id);
    }

    public function toggleCek($id)
    {
        if ($this->nota && $this->nota->id == $id) {
            $this->nota->cek = ! $this->nota->cek;
            $this->nota->save();
        }
    }

    public function togglePrint($id)
    {
        if ($this->nota && $this->nota->id == $id) {
            $this->nota->print = ! $this->nota->print;
            $this->nota->save();
        }
    }

}
