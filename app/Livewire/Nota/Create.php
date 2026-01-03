<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Create extends Component
{
    public $no_nota,$pembeli,$nama_toko,$alamat,$tanggal,$subtotal=0,$diskon_persen=0,$diskon_rupiah=0,$total_harga=0,$total_coly=0,$jt_tempo;
    public $surat_jalan_id = null;
    public $details = [];
    public $title = 'Buat Nota';

    // Untuk edit inline
    public $editIndex = null;
    public $editData = [];

    public $formDetail = [
        'nama_barang' => '',
        'coly' => 0,
        'satuan_coly' => '',
        'qty_isi' => 0,
        'nama_isi' => '',
        'harga' => 0,
        'diskon' => [], 
        'jumlah' => 0,
        'total' => 0,
    ];

    public function mount($id = null)
    {
        $this->resetForm();
        $this->tanggal  = Carbon::now()->toDateString();
        $this->jt_tempo = Carbon::now()->addMonths(2)->toDateString();
        $this->no_nota = Nota::generateNextNoNota($this->tanggal);

        if ($id) {
            $surat = \App\Models\SuratJalan::with('detailsj')->findOrFail($id);
            $this->surat_jalan_id = $id;
            $this->nama_toko = $surat->nama_toko;
            $this->alamat = $surat->alamat;
            $this->tanggal = $surat->tanggal;

            $this->jt_tempo = Carbon::parse($surat->tanggal)->addMonths(2)->toDateString();

            foreach ($surat->detailsj as $detail) {
                $this->details[] = [
                    'nama_barang'  => $detail->nama_barang,
                    'coly'         => $detail->coly,
                    'satuan_coly'  => $detail->satuan_coly ?? '',
                    'qty_isi'      => $detail->qty_isi,
                    'nama_isi'     => $detail->nama_isi ?? '',
                    'jumlah'       => $detail->coly * ($detail->qty_isi ?? 1),
                    'harga'        => 0,
                    'diskon'       => $detail->diskon ? explode(',', $detail->diskon) : [],
                    'total'        => 0,
                ];
            }
        }
    }

    public function updatedTanggal($value)
    {
        if ($value) {
            $this->no_nota = Nota::generateNextNoNota($value);
            $this->jt_tempo = Carbon::parse($value)->addMonths(2)->toDateString();
        }
    }

    public function setJatuhTempo($months)
    {
        if ($this->tanggal) {
            $this->jt_tempo = Carbon::parse($this->tanggal)->addMonths($months)->toDateString();
        }
    }

    public function resetForm()
    {
        $this->reset([
            'pembeli',
            'tanggal',
            'nama_toko',
            'alamat',
            'subtotal', 
            'diskon_persen',
            'diskon_rupiah',
            'total_harga',
            'total_coly',
            'jt_tempo',
            'details',
            'surat_jalan_id',
        ]);
    }

    private function recalcFooter()
    {
        $this->subtotal    = $this->getSubtotalProperty();
        $this->total_coly  = $this->getTotalColyProperty();
        $this->total_harga = $this->getTotalHargaProperty();
    }

    public function addDetail()
    {
        // Validasi minimal
        if (empty($this->formDetail['nama_barang'])) {
            session()->flash('error', 'Nama barang harus diisi!');
            return;
        }

        $this->formDetail['diskon'] = array_values(
            array_filter((array) $this->formDetail['diskon'], 'is_numeric')
        );

        $this->details[] = $this->formDetail;

        $index = count($this->details) - 1;
        $this->recalcRow($index);
        $this->recalcFooter();

        // Reset form
        $this->formDetail = [
            'nama_barang' => '',
            'coly' => 0,
            'satuan_coly' => '',
            'qty_isi' => 0,
            'nama_isi' => '',
            'harga' => 0,
            'diskon' => [],
            'jumlah' => 0,
            'total' => 0,
        ];

        $this->dispatch('focus-nama-barang');
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    // ========== EDIT METHODS ==========
    
    public function startEdit($index)
    {
        $this->editIndex = $index;
        $this->editData = $this->details[$index];
    }

    public function saveEdit()
    {       
            if ($this->editIndex === null) return;

            $i = $this->editIndex;

            // HANYA field yang memang pakai edit mode
            $this->details[$i]['nama_barang']  = $this->editData['nama_barang'];
            $this->details[$i]['coly']         = $this->editData['coly'];
            $this->details[$i]['satuan_coly']  = $this->editData['satuan_coly'];
            $this->details[$i]['qty_isi']      = $this->editData['qty_isi'];
            $this->details[$i]['nama_isi']     = $this->editData['nama_isi'];

            // Recalc
            $this->recalcRow($this->editIndex);
            $this->recalcFooter();

            // Reset edit mode
            $this->cancelEdit();        
    }

    public function cancelEdit()
    {
        $this->editIndex = null;
        $this->editData = [];
    }

    public function addDiskon($row)
    {
        if (!isset($this->details[$row]['diskon']) || !is_array($this->details[$row]['diskon'])) {
            $this->details[$row]['diskon'] = [];
        }
        $this->details[$row]['diskon'][] = 0;
    }

    public function removeDiskon($row, $index)
    {
        unset($this->details[$row]['diskon'][$index]);
        $this->details[$row]['diskon'] = array_values($this->details[$row]['diskon']);
    }

    public function updatedEditData($value, $name)
    {
        // Recalc saat edit data berubah
        if ($this->editIndex !== null) {
            $coly  = (int) ($this->editData['coly'] ?? 0);
            $qty   = (int) ($this->editData['qty_isi'] ?? 0);
            $harga = (int) ($this->editData['harga'] ?? 0);

            $diskon = array_sum(
                array_map('intval', (array) ($this->editData['diskon'] ?? []))
            );

            $jumlah = $coly * $qty;
            $total  = ($harga * $jumlah) * (1 - ($diskon / 100));

            $this->editData['jumlah'] = $jumlah;
            $this->editData['total']  = round($total);
        }
    }

    // ========== END EDIT METHODS ==========

    private function recalcRow($i)
    {
        if (!isset($this->details[$i])) return;

        $item = $this->details[$i];

        $coly  = (int) ($item['coly'] ?? 0);
        $qty   = (int) ($item['qty_isi'] ?? 0);
        $harga = (int) ($item['harga'] ?? 0);

        $diskon = array_sum(
            array_map('intval', (array) ($item['diskon'] ?? []))
        );

        $jumlah = $coly * $qty;
        $total  = ($harga * $jumlah) * (1 - ($diskon / 100));

        $this->details[$i]['jumlah'] = $jumlah;
        $this->details[$i]['total']  = round($total);
    }

    public function updatedDetails($value, $name)
    {
        if (preg_match('/details\.(\d+)\./', $name, $m)) {
            $this->recalcRow((int) $m[1]);
            $this->recalcFooter();
        }
    }

    public function store()
    {
        $this->dispatch('swal-loading', [
            'title' => 'Menyimpan Nota...',
            'message' => 'Mohon tunggu sebentar'
        ]);

        $this->validate([
            'no_nota' => 'required|unique:nota,no_nota',
            'pembeli' => 'nullable',
            'nama_toko' => 'required',
            'alamat' => 'required',
            'tanggal' => 'required|date',
        ]);

        if (empty($this->details)) {
            $this->dispatch('swal-close');
            session()->flash('error', 'Minimal harus ada 1 barang!');
            return;
        }

        // Paksa hitung ulang
        $this->subtotal     = $this->getSubtotalProperty();
        $this->total_coly   = $this->getTotalColyProperty();
        $this->total_harga  = $this->getTotalHargaProperty();
        
        // coba
        // foreach ($this->details as $d) {
        //         // $d['diskon'] = array_map(fn ($v)=> (int) $v, $d['diskon']);

        //         $diskonArr = array_map('intval', (array) ($d['diskon'] ?? []));
        //         $diskon    = array_sum($diskonArr);

        //         dd([
        //             'diskonArr' => $diskonArr,
        //             'diskon_sum' => $diskon,
        //         ]);
        //     }

        DB::transaction(function () {
            $nota = Nota::create([
                'no_nota'        => $this->no_nota,
                'pembeli'        => $this->pembeli,
                'nama_toko'      => $this->nama_toko,
                'alamat'         => $this->alamat,
                'tanggal'        => $this->tanggal,
                'subtotal'       => $this->subtotal,
                'diskon_persen'  => $this->diskon_persen ?? 0,
                'diskon_rupiah'  => $this->diskon_rupiah ?? 0,
                'total_harga'    => $this->total_harga,
                'total_coly'     => $this->total_coly,
                'jt_tempo'       => $this->jt_tempo,
                'status'         => 1,
            ]);

            // foreach ($this->details as $d) {
            //     $d['diskon'] = array_map(fn ($v)=> (int) $v, $d['diskon']);
            //     $nota->details()->create($d);
            // }

            foreach ($this->details as $d) {

                $coly   = (int) ($d['coly'] ?? 0);
                $qty    = (int) ($d['qty_isi'] ?? 0);
                $harga  = (int) ($d['harga'] ?? 0);

                $diskonArr = array_map('intval', (array) ($d['diskon'] ?? []));
                $diskon    = array_sum($diskonArr);

                $jumlah = $coly * $qty;
                $total  = ($harga * $jumlah) * (1 - ($diskon / 100));

                $nota->details()->create([
                    'nama_barang' => $d['nama_barang'],
                    'coly'        => $coly,
                    'satuan_coly' => $d['satuan_coly'],
                    'qty_isi'     => $qty,
                    'nama_isi'    => $d['nama_isi'],
                    'jumlah'      => $jumlah,
                    'harga'       => $harga,
                    'diskon'      => $diskonArr,
                    'total'       => round($total),
                ]);
            }

            if ($this->surat_jalan_id) {
                DB::table('surat_jalan')
                    ->where('id', $this->surat_jalan_id)
                    ->update([
                        'nota' => 1,
                        'nota_id' => $nota->id
                    ]);
            }
        });

        $this->dispatch('showSuccessAlert', [
            'message' => 'Nota berhasil disimpan!',
            'redirect' => route(name: 'nota.index')
        ]);
    }

    public function getSubtotalProperty()
    {
        return collect($this->details)->sum('total');
    }

    public function getTotalHargaProperty()
    {
        $subtotal = $this->getSubtotalProperty();

        if ($this->diskon_persen > 0) {
            return round($subtotal - ($subtotal * $this->diskon_persen / 100));
        }

        if ($this->diskon_rupiah > 0) {
            return round($subtotal - $this->diskon_rupiah);
        }

        return $subtotal;
    }

    public function updatedDiskonPersen($value)
    {
        if (is_numeric($value) && $this->subtotal > 0) {
            $this->diskon_rupiah = round(($this->subtotal * ((float)$value)) / 100, 0);
        } else {
            $this->diskon_rupiah = 0;
        }

        $this->total_harga = $this->getTotalHargaProperty();
        $this->recalcFooter();
    }

    public function updatedDiskonRupiah($value)
    {
        if (is_numeric($value) && $this->subtotal > 0) {
            $this->diskon_persen = round(((float)$value / $this->subtotal) * 100, 2);
        } else {
            $this->diskon_persen = 0;
        }

        $this->total_harga = $this->getTotalHargaProperty();
        $this->recalcFooter();
    }

    public function getTotalColyProperty()
    {
        return collect($this->details)->sum('coly');
    }

    // FORM BARU - Diskon methods
    public function addFormDiskon()
    {
        if (!is_array($this->formDetail['diskon'])) {
            $this->formDetail['diskon'] = [];
        }
        $this->formDetail['diskon'][] = 0;
    }

    public function removeFormDiskon($index)
    {
        unset($this->formDetail['diskon'][$index]);
        $this->formDetail['diskon'] = array_values($this->formDetail['diskon']);
    }

    public function render()
    {
        // $this->subtotal = $this->getSubtotalProperty();
        // $this->total_coly = $this->getTotalColyProperty();
        // $this->total_harga = $this->getTotalHargaProperty();

        return view('livewire.nota.create');
    }
}