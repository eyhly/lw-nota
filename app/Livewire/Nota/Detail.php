<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use App\Models\DetailNota;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
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

    // Data untuk add new item
    public $isAdding = false;
    public $newItem = [];

    #[Layout('layouts.app')]
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

        $this->resetNewItem();
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
            'nama_barang' => '',
            'coly'        => 0,
            'satuan_coly' => '',
            'qty_isi'     => 0,
            'nama_isi'    => '',
            'jumlah'      => 0,
            'harga'       => 0,
            'diskon'      => [],
            'total'       => 0,
        ];
    }

    public function saveNewItem()
    {
        $this->validate([
            'newItem.nama_barang' => 'required|string',
            'newItem.coly'        => 'required|numeric|min:0',
            'newItem.satuan_coly' => 'required|string',
            'newItem.qty_isi'     => 'required|numeric|min:0',
            'newItem.nama_isi'    => 'required|string',
            'newItem.harga'       => 'required|numeric|min:0',
        ]);

        // Calculate jumlah
        $this->newItem['jumlah'] = (float) $this->newItem['coly'] * (float) $this->newItem['qty_isi'];

        $subtotal = $this->newItem['jumlah'] * (float) $this->newItem['harga'];
       
        $this->newItem['total'] = $subtotal;

        foreach ((array) ($this->newItem['diskon'] ?? []) as $d) {
            $this->newItem['total'] -=
                $this->newItem['total'] * ((float) $d / 100);
        }

        // Format diskon for DB
        $diskonString = array_map(fn($v) => (int) $v, $this->newItem['diskon']);

        // Create new detail
        DetailNota::create([
            'nota_id'     => $this->nota->id,
            'nama_barang' => $this->newItem['nama_barang'],
            'coly'        => $this->newItem['coly'],
            'satuan_coly' => $this->newItem['satuan_coly'],
            'qty_isi'     => $this->newItem['qty_isi'],
            'nama_isi'    => $this->newItem['nama_isi'],
            'jumlah'      => $this->newItem['jumlah'],
            'harga'       => $this->newItem['harga'],
            'diskon'      => $diskonString,
            'total'       => $this->newItem['total'],
        ]);

        $subtotalNota = DetailNota::where('nota_id', $this->nota->id)->sum('total');
        $totalNota = $subtotalNota - ($this->diskon_rupiah ?? 0);
        $totalNota = $totalNota - ($totalNota * (($this->diskon_persen ?? 0) / 100));

        Nota::where('id', $this->nota->id)->update([
            'subtotal' => $subtotalNota,
            'total_harga' => $totalNota,
        ]);    

        // Refresh data
        $this->nota = Nota::with('details')->find($this->nota->id);

        $this->recalcTotalHarga();

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

    public function addNewItemDiskon()
    {
        if (!is_array($this->newItem['diskon'])) {
            $this->newItem['diskon'] = [];
        }

        $this->newItem['diskon'][] = 0;
    }

    public function removeNewItemDiskon($index)
    {
        unset($this->newItem['diskon'][$index]);
        $this->newItem['diskon'] = array_values($this->newItem['diskon']);
    }

    public function updatedNewItem($value, $name)
    {
        if (
            str_contains($name, 'coly') ||
            str_contains($name, 'qty_isi') ||
            str_contains($name, 'harga') ||
            str_contains($name, 'diskon')
        ) {
            $this->recalcNewItemTotal();
        }
    }

    private function recalcNewItemTotal()
    {
        if (empty($this->newItem)) return;

        $coly  = (float) ($this->newItem['coly'] ?? 0);
        $qty   = (float) ($this->newItem['qty_isi'] ?? 0);
        $harga = (float) ($this->newItem['harga'] ?? 0);

        $diskon = array_sum(
            array_map('floatval', (array) ($this->newItem['diskon'] ?? []))
        );

        $diskon = min($diskon, 100);

        $jumlah = $coly * $qty;
        $subtotal = $jumlah * $harga;

        $this->newItem['jumlah'] = $jumlah;
        $this->newItem['total']  = round(
            $subtotal * (1 - ($diskon / 100))
        );
    }

    public function startEdit($index, $detailId)
    {
        $this->editIndex = $index;
        $this->isAdding = false;
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

        $this->recalcEditTotal();
    }

    public function saveEdit()
    {
        $detail = DetailNota::find($this->editData['id']);

        // jumlah barang
        $this->editData['jumlah'] =
            (float) $this->editData['coly'] * (float) $this->editData['qty_isi'];

        // total diskon persen
        // $diskon = array_sum(
        //     array_map('floatval', (array) ($this->editData['diskon'] ?? []))
        // );

        // subtotal
        $subtotal = $this->editData['jumlah'] * (float) $this->editData['harga'];

        $this->editData["total"] = $subtotal;

        foreach ($this->editData["diskon"] as $d)
            $this->editData["total"] -= $this->editData["total"] * (float) $d / 100;

        $detail->update($this->editData);

        // dd($this->editData["total"]);

        // total setelah diskon
        // $this->editData['total'] =
        //     $subtotal * (1 - ($diskon / 100));

        // simpan diskon ke DB
        // $this->editData['diskon'] = array_map(fn($v) => (int) $v, $this->editData['diskon']);

        $subtotalNota = DetailNota::where('nota_id', $detail->nota_id)
            ->sum('total');
        $totalNota = $subtotalNota - $this->nota->diskon_rupiah;
        $totalNota = $totalNota - ($totalNota*($this->nota->diskon_persen/100));
        
        // update nota
        Nota::where('id', $detail->nota_id)
            ->update([
                'subtotal' => $subtotalNota,
                'total_harga' => $totalNota,
            ]);

        // refresh data
        $this->nota = Nota::with('details')->find($this->nota->id);
        
        $this->dispatch('$refresh');
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

    public function updatedDiskonPersen($value)
    {
        $this->subtotal = $this->nota->details->sum('total');

        if (is_numeric($value) && $this->subtotal > 0) {
            $this->diskon_rupiah = round(($this->subtotal * ((float)$value)) / 100, 0);
        } else {
            $this->diskon_rupiah = 0;
        }
        
        $this->total_harga = max(0, $this->subtotal - $this->diskon_rupiah);
        
        $this->nota->update([
            'diskon_persen' => $this->diskon_persen,
            'diskon_rupiah' => $this->diskon_rupiah,
            'total_harga'   => $this->total_harga,
        ]);
    }


    public function updatedDiskonRupiah($value)
    {
        $this->subtotal = $this->nota->details->sum('total');

        if (is_numeric($value) && $this->subtotal > 0) {
            $this->diskon_persen = round(((float)$value / $this->subtotal) * 100, 2);
        } else {
            $this->diskon_persen = 0;
        }

        
        $this->total_harga = max(0, $this->subtotal - $this->diskon_rupiah);
       
        $this->nota->update([
            'diskon_persen' => $this->diskon_persen,
            'diskon_rupiah' => $this->diskon_rupiah,
            'total_harga'   => $this->total_harga,
        ]);
    }


    public function recalcTotalHarga()
    {
        $subtotal = $this->nota->details->sum('total');

        $diskonPersen = $this->diskon_persen ?? 0;
        $diskonRupiah = $this->diskon_rupiah ?? 0;

        $this->subtotal = $subtotal;
        $this->total_harga = max(0, $subtotal - $diskonRupiah - ($subtotal * ($diskonPersen / 100)));
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

        $coly  = (float) ($this->editData['coly'] ?? 0);
        $qty   = (float) ($this->editData['qty_isi'] ?? 0);
        $harga = (float) ($this->editData['harga'] ?? 0);

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

    public function pdf(Request $request)
    {
        $ids = explode(',', $request->ids);

        $notas = Nota::whereIn('id', $ids)->get();

        if ($notas->count() !== count($ids)) {
            abort(404, 'Ada nota yang tidak ditemukan');
        }

        Nota::whereIn('id', $ids)->update(['print' => 1]);

        $nota = Nota::with('details')
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        $data = array(
            'title' => 'Detail Nota',
            'notas' => $nota,
            // 'chunks' => $chunks
        );
        $customPaper = array(0, 0, 595, 395);
        $pdf = FacadePdf::loadView('pdf.index', $data)->setPaper($customPaper);
        return $pdf->stream('nota.pdf');
    }

    public function render()
    {
        $this->subtotal    = $this->getSubtotalProperty();
        $this->total_coly  = $this->getTotalColyProperty();
        $this->total_harga = $this->getTotalHargaProperty();

        return view('livewire.nota.detail');
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
