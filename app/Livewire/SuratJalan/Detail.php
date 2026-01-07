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

    public $no_surat, $nama_toko, $alamat, $tanggal;

    public $editIndex = null;
    public $editData = [];

    // Data untuk add new item
    public $isAdding = false;
    public $newItem = [];

    public function mount($id)
    {
        $this->suratjalan = SuratJalan::with('detailsj')->findOrFail($id);

        $this->no_surat     = $this->suratjalan->no_surat;
        $this->nama_toko     = $this->suratjalan->nama_toko;
        $this->alamat     = $this->suratjalan->alamat;
        $this->tanggal     = $this->suratjalan->tanggal;

        $this->resetNewItem();
    }

    public function updateSurat()
    {
        $this->validate([
            'no_surat'     => 'required|string',
            'nama_toko'     => 'required|string',
            'alamat'     => 'required|string',
            'tanggal'     => 'required|date',
        ]);

        $this->suratjalan->update([
            'no_surat'     => $this->no_surat,
            'nama_toko'     => $this->nama_toko,
            'alamat'     => $this->alamat,
            'tanggal'     => $this->tanggal,
        ]);

        session()->flash('success', 'Data surat jalan berhasil diperbarui.');
    }

    // tambah barang

    public function startAdding()
    {
        $this->isAdding = true;
        $this->editIndex = null;
        $this->resetNewItem();
    }

    public function resetNewItem()
    {
        $this->newItem = [
            'coly'        => 0,
            'satuan_coly' => '',
            'qty_isi'     => 0,
            'nama_isi'    => '',
            'nama_barang' => '',
            'keterangan' => '',
        ];
    }

    public function saveNewItem()
    {
        $this->validate([
            'newItem.coly'        => 'required|numeric|min:0',
            'newItem.satuan_coly' => 'required|string',
            'newItem.qty_isi'     => 'required|numeric|min:0',
            'newItem.nama_isi'    => 'required|string',
            'newItem.nama_barang' => 'required|string',
            'newItem.keterangan'  => 'nullable|string',
        ]);

        // Create new detail
        DetailSuratJalan::create([
            's_jalan_id'        => $this->suratjalan->id,
            'coly'              => $this->newItem['coly'],
            'satuan_coly'       => $this->newItem['satuan_coly'],
            'qty_isi'           => $this->newItem['qty_isi'],
            'nama_isi'          => $this->newItem['nama_isi'],
            'nama_barang'       => $this->newItem['nama_barang'],
            'keterangan'        => $this->newItem['keterangan'],
        ]);

        // Refresh data
        $this->suratjalan = SuratJalan::with('detailsj')->find($this->suratjalan->id);

        // Reset
        $this->isAdding = false;
        $this->resetNewItem();

        session()->flash('message', 'Item berhasil ditambahkan!');
    }

    public function cancelAdding()
    {
        $this->isAdding = false;
        $this->resetNewItem();
    }


    public function startEdit($index, $detailId)
    {
        $this->editIndex = $index;
        $detail = $this->suratjalan->detailsj->find($detailId);

        $this->editData = [
            'id'          => $detail->id,
            'coly'        => $detail->coly,
            'satuan_coly' => $detail->satuan_coly,
            'qty_isi'     => $detail->qty_isi,
            'nama_isi'    => $detail->nama_isi,
            'nama_barang' => $detail->nama_barang,
            'keterangan'  => $detail->keterangan,
        ];
    }

    public function updatedEditData($value, $field)
    {
        // Kalau coly berubah → langsung hitung ulang total coly
        if ($field === 'coly') {
            $this->editData['coly'] = (float) $value;
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


    public function pdf($id)
    {
        $suratjalan = SuratJalan::with('detailsj')->findOrFail($id);

        // $chunks = $suratjalan->detailsj->chunk(10);

        $data = array(
            'title' => 'Detail SuratJalan',
            'suratjalan' => $suratjalan,
            // 'chunks' => $chunks,
            // 'startNumber' => 0 
        );
        $customPaper = array(0, 0, 595, 395);
        $pdf = Pdf::loadView('pdf.surat', $data)->setPaper($customPaper);
        // $pdf = Pdf::loadView('pdf.surat', $data)->setPaper('a5', 'landscape');
        return $pdf->stream('suratjalan.pdf');
    }

    public function render()
    {
        return view('livewire.suratjalan.detail')->layout('layouts.app');
    }

    public function updatePrintAndRedirectSJ($id)
    {
        $suratjalan = SuratJalan::findOrFail($id);

        // update print = 1
        $suratjalan->update(['print' => 1]);

        // redirect ke halaman PDF
        return redirect()->route('pdf.surat', $id);
    }

    public function toggleNota($id)
    {
        if ($this->suratjalan && $this->suratjalan->id == $id) {
            $this->suratjalan->nota = ! $this->suratjalan->nota;
            $this->suratjalan->save();
        }
    }

    public function togglePrint($id)
    {
        if ($this->suratjalan && $this->suratjalan->id == $id) {
            $this->suratjalan->print = ! $this->suratjalan->print;
            $this->suratjalan->save();
        }
    }
}
