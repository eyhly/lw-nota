<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';    

    public $no_surat, $tanggal, $pembeli, $kendaraan, $no_kendaraan, $total_coly, $status, $suratjalan_id;
   
    public function render()
    {
        $data = array(
            'title' => 'Data Surat Jalan',
            'suratjalan' => SuratJalan::where('pembeli', 'like','%'.$this->search.'%')
            ->orWhere('status', 'like','%'.$this->search.'%')
            ->orderBy('status', 'desc')->paginate($this->paginate),
        );
        return view('livewire.suratjalan.index', $data);
    }     
    
    public function confirm($id){
        $suratjalan = SuratJalan::findOrFail($id);

        $this->pembeli = $suratjalan->pembeli;
        $this->tanggal = $suratjalan->tanggal;
        $this->suratjalan_id = $suratjalan->id;
    }

    public function destroy($id){
        $suratjalan = SuratJalan::findOrFail($id);
        $suratjalan->delete();
        $this->dispatch('closeDeleteModal'); 
    }
        
}
