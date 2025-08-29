<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $no_nota,$pembeli,$alamat,$tanggal,$subtotal=0,$diskon_persen=0,$diskon_rupiah=0,$total_harga=0,$total_coly=0,$jt_tempo;
    public $details = [];
    public $title = 'Tambah Nota';

    public $formDetail = [
        'nama_barang' => '',
        'coly' => 0,
        'satuan_coly' => '',
        'qty_isi' => 0,
        'nama_isi' => '',
        'harga' => 0,
        'diskon' => 0, 
        'jumlah' => 0,
        'total' => 0,
    ];

    public function mount($id = null)
    {
        $this->resetForm();
        // otomatis generate nomor baru
        $this->no_nota = Nota::generateNextNoNota();
        $this->tanggal = now()->toDateString();
        $this->jt_tempo = now()->addMonths(2)->toDateString();

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
                    'diskon'       => 0,
                    'total'        => 0,
                ];
            }
        }
    }

    public function resetForm()
    {
        $this->reset([
            'pembeli',
            'tanggal',
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

    public function updatedFormDetail($value, $key)
    {
        // hitung otomatis jumlah & total saat input berubah
        if (in_array($key, ['coly','qty_isi','harga','diskon'])) {
            $coly = (int) $this->formDetail['coly'];
            $qty  = (int) $this->formDetail['qty_isi'];
            $harga = (int) $this->formDetail['harga'];
            $diskon = (int) ($this->formDetail['diskon'] ?? 0);

            $jumlah = $coly * $qty;
            $total  = ($harga * $jumlah) - $diskon;

            $this->formDetail['jumlah'] = $jumlah;
            $this->formDetail['total']  = $total;
        }
    }

    public function addDetail()
    {
        if (empty($this->formDetail['diskon'])) {
            $this->formDetail['diskon'] = 0;
        }

        $this->details[] = $this->formDetail;

        $this->formDetail = [
            'nama_barang' => '',
            'coly' => 0,
            'satuan_coly' => '',
            'qty_isi' => 0,
            'nama_isi' => '',
            'harga' => 0,
            'diskon' => 0,
            'jumlah' => 0,
            'total' => 0,
        ];
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    public function store()
    {
        $this->validate([
            'no_nota' => 'required|unique:nota,no_nota',
            'pembeli' => 'required',
            'alamat' => 'required',
            'tanggal' => 'required|date',
        ]);

        DB::transaction(function() {
            $nota = Nota::create([
                'no_nota' => $this->no_nota,
                'pembeli' => $this->pembeli,
                'alamat' => $this->alamat,
                'tanggal' => $this->tanggal,
                'subtotal' => $this->subtotal,
                'diskon_persen' => $this->diskon_persen ?? 0,
                'diskon_rupiah' => $this->diskon_rupiah ?? 0,
                'total_harga' => $this->total_harga,
                'total_coly' => $this->total_coly,
                'jt_tempo' => $this->jt_tempo,
            ]);

            foreach($this->details as $d) {
                $nota->details()->create($d);
            }
        });

        session()->flash('success','Nota berhasil disimpan');
        return redirect()->route('nota.index');
    }

    public function getSubtotalProperty()
    {
        return collect($this->details)->sum('total');
    }

    public function getTotalHargaProperty()
    {
        return $this->subtotal - ($this->diskon_rupiah ?? 0);
    }

    public function getTotalColyProperty()
    {
        return collect($this->details)->sum('coly');
    }

    public function render()
    {
        $this->subtotal = $this->getSubtotalProperty();
        $this->total_harga = $this->getTotalHargaProperty();
        $this->total_coly = $this->getTotalColyProperty();

        return view('livewire.nota.create');
    }
}
