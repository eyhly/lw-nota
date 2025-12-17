<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Create extends Component
{
    public $no_nota,$pembeli,$nama_toko,$alamat,$tanggal,$subtotal=0,$diskon_persen=0,$diskon_rupiah=0,$total_harga=0,$total_coly=0,$jt_tempo;
    public $details = [];
    public $title = 'Tambah Nota';

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
        // otomatis generate nomor baru
        $this->tanggal  = Carbon::now()->toDateString();
        $this->jt_tempo = Carbon::now()->addMonths(2)->toDateString();
        $this->no_nota = Nota::generateNextNoNota($this->tanggal);

        if ($id) {
            $surat = \App\Models\SuratJalan::with('detailsj')->findOrFail($id);

            $this->pembeli = $surat->pembeli;

            foreach ($surat->detailsj as $detail) {
                $this->details[] = [
                    'nama_barang'  => $detail->nama_barang,
                    'coly'         => $detail->coly,
                    'satuan_coly'  => $detail->satuan_coly ?? '',
                    'qty_isi'      => $detail->qty_isi ?? 1,
                    'nama_isi'     => $detail->nama_isi ?? '',
                    'jumlah'       => $detail->coly * ($detail->qty_isi ?? 1),
                    'harga'        => 0,
                    'diskon'      => $detail->diskon
                                    ? explode(',', $detail->diskon)
                                    : [],
                    'total'        => 0,
                ];
            }
        }
    }

    public function updatedTanggal($value)
    {
        if ($value) {
            $this->no_nota = Nota::generateNextNoNota($value);

            // opsional: update jatuh tempo ikut tanggal
            $this->jt_tempo = Carbon::parse($value)->addMonths(2)->toDateString();
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
        ]);
    }

    public function addDetail()
    {
        $this->formDetail['diskon'] = array_values(
            array_filter((array) $this->formDetail['diskon'], 'is_numeric')
        );

        $this->details[] = $this->formDetail;

        $index = count($this->details) - 1;
        $this->recalcRow($index);

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
    }


    public function removeDetail($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

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
        }
    }

    public function store()
    {
        $this->validate([
            'no_nota' => 'required|unique:nota,no_nota',
            'pembeli' => 'required',
            'nama_toko' => 'required',
            'alamat' => 'required',
            'tanggal' => 'required|date',
        ]);

        DB::transaction(function() {
            $nota = Nota::create([
                'no_nota' => $this->no_nota,
                'pembeli' => $this->pembeli,
                'nama_toko' => $this->nama_toko,
                'alamat' => $this->alamat,
                'tanggal' => $this->tanggal,
                'subtotal' => $this->subtotal,
                'diskon_persen' => $this->diskon_persen ?? 0,
                'diskon_rupiah' => $this->diskon_rupiah ?? 0,
                'total_harga' => $this->total_harga,
                'total_coly' => $this->total_coly,
                'jt_tempo' => $this->jt_tempo,
                'status'         => 1,
            ]);

            foreach($this->details as $d) {
                $d['diskon'] = implode(',', $d['diskon'] ?? []);
                $nota->details()->create($d);
            }
        });

        // session()->flash('success','Nota berhasil disimpan');
        // return redirect()->route('nota.index');

        $this->dispatch('showSuccessAlert');
    }

    public function getSubtotalProperty()
    {
        return collect($this->details)->sum('total');
    }

    public function getTotalHargaProperty()
    {
        if ($this->diskon_persen > 0) {
            $this->diskon_rupiah = ($this->subtotal * $this->diskon_persen) / 100;
            return $this->subtotal - $this->diskon_rupiah;
        }

        return $this->subtotal - ($this->diskon_rupiah ?? 0);
    }

    public function updatedDiskonPersen($value)
    {
        if (is_numeric($value) && $this->subtotal > 0) {
            $this->diskon_rupiah = round(($this->subtotal * ((float)$value)) / 100, 0);
        } else {
            $this->diskon_rupiah = 0;
        }
    }

    public function updatedDiskonRupiah($value)
    {
        if (is_numeric($value) && $this->subtotal > 0) {
            $this->diskon_persen = round(((float)$value / $this->subtotal) * 100, 2);
        } else {
            $this->diskon_persen = 0;
        }
    }

    public function getTotalColyProperty()
    {
        return collect($this->details)->sum('coly');
    }

    public function addDiskon($index)
    {
        if (!is_array($this->details[$index]['diskon'])) {
            $this->details[$index]['diskon'] = [];
        }

        $this->details[$index]['diskon'][] = 0;
    }

    public function removeDiskon($index, $diskonIndex)
    {
        unset($this->details[$index]['diskon'][$diskonIndex]);
        $this->details[$index]['diskon'] = array_values($this->details[$index]['diskon']);
    }

    // FORM BARU
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
        $this->subtotal = $this->getSubtotalProperty();
        $this->total_harga = $this->getTotalHargaProperty();
        $this->total_coly = $this->getTotalColyProperty();

        return view('livewire.nota.create');
    }
}
