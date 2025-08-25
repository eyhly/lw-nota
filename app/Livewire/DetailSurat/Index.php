<?php

namespace App\Livewire\SuratJalan;

use App\Models\DetailSuratJalan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';

    public $s_jalan_id,$nama_barang,$coly,$isi,$status;

    public function render()
    {
        $data = array(
            'title' => 'Detail Surat Jalan',
            'suratjalan' => SuratJalan::where('nama_barang', 'like','%'.$this->search.'%')
            ->orWhere('status', 'like','%'.$this->search.'%')
            ->orderBy('status', 'desc')->paginate($this->paginate),
        );
        return view('livewire.detailSuratJalan.index', $data);
    }


    public function create(){
        $this->resetValidation();
        $this->reset([
            's_jalan_id',
            'nama_barang',
            'coly',
            'isi',
            'status',
        ]);
    }

    public function storeDetail()
    {
        $this->validate([
            's_jalan_id' => 'required|exists:suratjalan,id',
            'nama_barang' => 'required|string',
            'coly' => 'nullable|numeric',            
            'isi' => 'nullable|string',
            'status' => 'required|string',
        ]);

        DetailSuratJalan::create([
            's_jalan_id' => $this->s_jalan_id,
            'nama_barang' => $this->nama_barang,
            'coly' => $this->coly,
            'isi' => $this->isi,
            'status' => $this->status,
        ]);

        $this->dispatch('closeCreateDetailModal');
    }


    public function confirm($id){
        $user = DetailSuratJalan::findOrFail($id);
    }

    public function destroy($id){
        $user = DetailSuratJalan::findOrFail($id);
        $user->delete();
        $this->dispatch('closeDeleteModal'); 
    }
    
}
