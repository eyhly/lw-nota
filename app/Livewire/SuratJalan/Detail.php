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

    public function ejaan_angka($angka)
    {
        $kata = [
            0 => 'nol',
            1 => 'satu',
            2 => 'dua',
            3 => 'tiga',
            4 => 'empat',
            5 => 'lima',
            6 => 'enam',
            7 => 'tujuh',
            8 => 'delapan',
            9 => 'sembilan',
            10 => 'sepuluh',
            11 => 'sebelas',
            12 => 'dua belas',
            13 => 'tiga belas',
            14 => 'empat belas',
            15 => 'lima belas',
            16 => 'enam belas',
            17 => 'tujuh belas',
            18 => 'delapan belas',
            19 => 'sembilan belas',
            20 => 'dua puluh',
            30 => 'tiga puluh',
            40 => 'empat puluh',
            50 => 'lima puluh',
            60 => 'enam puluh',
            70 => 'tujuh puluh',
            80 => 'delapan puluh',
            90 => 'sembilan puluh',
            100 => 'seratus',
        ];

        if (isset($kata[$angka])) {
            return $kata[$angka];
        }

        if ($angka < 100) {
            $puluhan = floor($angka / 10) * 10;
            $satuan  = $angka % 10;
            return $kata[$puluhan] . ' ' . $kata[$satuan];
        }

        return null;
    }

    public function pdf($id)
    {
        $suratjalan = SuratJalan::with('detailsj')->findOrFail($id);
        $jumlah_terbilang = $this->ejaan_angka($suratjalan['total_coly']);

        $data = array(
            'title' => 'Detail SuratJalan',
            'suratjalan' => $suratjalan,
            'total_coly_terbilang' => $jumlah_terbilang,
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
