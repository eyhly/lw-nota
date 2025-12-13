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
    public array $selectedIds = [];
    public string $bulkAction = '';

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

    public function runBulkAction()
    {
        if (empty($this->selectedIds)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Pilih data terlebih dahulu'
            ]);
            return;
        }

        switch ($this->bulkAction) {

            case 'delete':
                SuratJalan::whereIn('id', $this->selectedIds)->delete();
                break;

            case 'approve':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['cek' => 1]);
                break;

            case 'print':
                $ids = implode(',', $this->selectedIds);

                // reset supaya tidak ke-trigger ulang
                $this->reset(['selectedIds', 'bulkAction']);

                return redirect()->route('nota.print.bulk', [
                    'ids' => $ids
                ]);

            default:
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => 'Aksi tidak valid'
                ]);
                return;
        }

        // reset setelah aksi
        $this->reset(['selectedIds', 'bulkAction']);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Bulk action berhasil'
        ]);
    }

    public function toggleSelectAll()
    {
        if (count($this->selectedIds) === SuratJalan::count()) {
            $this->selectedIds = [];
        } else {
            $this->selectedIds = SuratJalan::pluck('id')->toArray();
        }
    }


    public function printBulk(Request $request)
    {
        $ids = explode(',', $request->ids);
        $notas = SuratJalan::whereIn('id', $ids)->get();

        return view('nota.print-bulk', compact('notas'));
    }
        
}
