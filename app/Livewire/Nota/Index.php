<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use App\Models\DetailNota;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';    

    public $no_nota,$pembeli,$tanggal,$subtotal,$diskon_persen,$diskon_rupiah,$total_harga,$total_coly,$jt_tempo,$nota_id;
   
    public function render()
    {
        $data = array(
            'title' => 'List Nota',
            'nota' => Nota::where('pembeli', 'like','%'.$this->search.'%')
            ->orWhere('tanggal', 'like','%'.$this->search.'%')
            ->orderBy('tanggal', 'desc')->paginate($this->paginate),
        );
        return view('livewire.nota.index', $data);
    }       
        
}
