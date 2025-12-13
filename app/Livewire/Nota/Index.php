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
    public array $selectedIds = [];
    public string $bulkAction = '';  

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
    
    public function confirm($id){
        $nota = Nota::findOrFail($id);

        $this->pembeli = $nota->pembeli;
        $this->tanggal = $nota->tanggal;
        $this->nota_id = $nota->id;
    }

    public function destroy($id){
        $nota = Nota::findOrFail($id);
        $nota->delete();
        $this->dispatch('closeDeleteModal'); 
    }

    public function toggleCek($id)
    {
        $item = Nota::find($id);

        if ($item) {
            $item->cek = $item->cek == 1 ? 0 : 1;
            $item->save();
        }
    }

    public function markPrinted($id)
    {
        $nota = Nota::findOrFail($id);

        // Update jadi sudah print
        $nota->print = 1;
        $nota->save();

        // Buka halaman print
        return redirect()->route('pdf.index', $id);
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
                Nota::whereIn('id', $this->selectedIds)->delete();
                break;

            case 'approve':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['cek' => 1]);
                break;

            case 'status':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['status' => 1]);
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
        if (count($this->selectedIds) === Nota::count()) {
            $this->selectedIds = [];
        } else {
            $this->selectedIds = Nota::pluck('id')->toArray();
        }
    }


    public function printBulk(Request $request)
    {
        $ids = explode(',', $request->ids);
        $notas = Nota::whereIn('id', $ids)->get();

        return view('nota.print-bulk', compact('notas'));
    }


        
}
