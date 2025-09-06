<?php

namespace App\Livewire\Nota;

use App\Models\DetailNota;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use PDF;

class Details extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';

    public $nota_id,$nama_barang,$coly,$satuan_coly,$qty_isi,$nama_isi,$jumlah,$harga,$diskon,$total;

    public function render()
    {
        $data = array(
            'title' => 'Detail Nota',
            'nota' => Nota::where('pembeli', 'like','%'.$this->search.'%')
            ->orWhere('tanggal', 'like','%'.$this->search.'%')
            ->orderBy('tanggal', 'desc')->paginate($this->paginate),
        );
        return view('livewire.detailNota.index', $data);
    }

    public function pdf($id){
        $nota = Nota::with('details')->findOrFail($id);
        $data = array(
            'title' => 'Detail Nota',
            'nota' => $nota,
        );
        $pdf = Pdf::loadView('pdf.index', $data)->setPaper('a4', 'landscape');;
        return $pdf->stream('nota.pdf');
    }


    public function create(){
        $this->resetValidation();
        $this->reset([
            'nota_id',
            'nama_barang',
            'coly',
            'satuan_coly',
            'qty_isi',
            'nama_isi',
            'jumlah',
            'harga',
            'diskon',
            'total',
        ]);
    }

    public function storeDetail()
    {
        $this->validate([
            'nota_id' => 'required|exists:nota,id',
            'nama_barang' => 'required|string',
            'coly' => 'nullable|numeric',
            'satuan_coly' => 'nullable|string',
            'qty_isi' => 'nullable|numeric',
            'nama_isi' => 'nullable|string',
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'total' => 'required|numeric',
        ]);

        DetailNota::create([
            'nota_id' => $this->nota_id,
            'nama_barang' => $this->nama_barang,
            'coly' => $this->coly,
            'satuan_coly' => $this->satuan_coly,
            'qty_isi' => $this->qty_isi,
            'nama_isi' => $this->nama_isi,
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
            'diskon' => $this->diskon ?? 0,
            'total' => $this->total,
        ]);

        $this->dispatch('closeCreateDetailModal');
    }


    public function confirm($id){
        $user = DetailNota::findOrFail($id);
    }

    public function destroy($id){
        $user = DetailNota::findOrFail($id);
        $user->delete();
        $this->dispatch('closeDeleteModal'); 
    }
    
}
