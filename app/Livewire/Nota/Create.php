<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use App\Models\DetailNota;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $no_nota,$pembeli,$tanggal,$subtotal,$diskon_persen,$diskon_rupiah,$total_harga,$total_coly,$jt_tempo;
    public $details = [];
    public $title = 'Tambah Nota';

    public function mount()
    {
        $this->resetForm();
    }

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
    public $diskonPersen = 0;
    public $diskonRupiah = 0;

    public function resetForm()
    {
        $this->reset([
            'no_nota',
            'pembeli',
            'tanggal',
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
        $this->formDetail['jumlah'] = $this->formDetail['coly'] * $this->formDetail['qty_isi'];
        $this->formDetail['total'] = $this->formDetail['harga'] * $this->formDetail['jumlah'];

        $this->barang[] = $this->formDetail;
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

    public function store()
    {
        $this->validate([
            'no_nota' => 'required|unique:nota,no_nota',
            'pembeli' => 'required',
            'tanggal' => 'required|date',
        ]);

        DB::transaction(function() {
            $nota = Nota::create([
                'no_nota' => $this->no_nota,
                'pembeli' => $this->pembeli,
                'tanggal' => $this->tanggal,
                'subtotal' => $this->subtotal,
                'diskon_persen' => $this->diskon_persen,
                'diskon_rupiah' => $this->diskon_rupiah,
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
        return collect($this->barang)->sum('total');
    }

    public function getTotalHargaProperty()
    {
        return $this->subtotal - $this->diskonRupiah;
    }

    public function render()
    {
        return view('livewire.nota.create');
    }
}
