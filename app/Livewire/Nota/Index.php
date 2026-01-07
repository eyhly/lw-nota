<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use App\Models\DetailNota;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';  
    public bool $selectAll = false;
    public array $selectedIds = [];
    public string $bulkAction = '';
    public $sortField = null;
    public $sortDirection = null;
    public $showFilter = false;
    public $filterYear;
    public $filterMonth;
    public $tempYear;
    public $tempMonth;

    public $no_nota,$pembeli,$tanggal,$subtotal,$diskon_persen,$diskon_rupiah,$total_harga,$total_coly,$jt_tempo,$nota_id;
   
    public function render()
    {
        return view('livewire.nota.index', [
            'title' => 'List Nota',
            'nota' => $this->baseQuery()->paginate($this->paginate),
        ]);
    }

    public function mount()
    {
        $this->filterYear = now()->year; 
        $this->filterMonth = null;


        $this->tempYear = $this->filterYear;
        $this->tempMonth = $this->filterMonth;
    }

    private function baseQuery()
    {
        $query = Nota::where(function ($q) {
            $q->where('nama_toko', 'like', '%' . $this->search . '%')
            ->orWhere('status', 'like', '%' . $this->search . '%');
        });

        //filter untujj tahun
        if ($this->filterYear) {
            $query->whereYear('tanggal', $this->filterYear);
        }
        
        if ($this->filterMonth) {
            $query->whereMonth('tanggal', $this->filterMonth);
        }

        if ($this->sortField && in_array($this->sortDirection, ['asc', 'desc'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // DEFAULT: ID terbaru
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    public function toggleFilter()
    {
        $this->showFilter = ! $this->showFilter;        
    }

    public function applyFilter()
    {
        $this->filterYear = $this->tempYear;
        $this->filterMonth = $this->tempMonth;

        $this->resetPage();
    }

    public function updatingPaginate()
    {
        $this->resetPage();
        $this->reset(['selectedIds', 'selectAll']);
    }

    public function sortBy($field)
    {
        // Jika klik field yang sama
        if ($this->sortField === $field) {

            // ASC → DESC
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            }
            // DESC → RESET (kembali ke default)
            else {
                $this->sortField = null;
                $this->sortDirection = null;
            }

        } 
        // Jika klik field baru
        else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->reset(['selectedIds', 'selectAll']);
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

    // Method yang dipanggil setelah konfirmasi
    #[On('run-bulk-action')]
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
                $message = 'Data berhasil dihapus';
                break;

            case 'approve':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['cek' => 1]);
                $message = 'Data berhasil dicek';
                break;

            case 'unapprove':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['cek' => 0]);
                $message = 'Data batal dicek';
                break;

            case 'status':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['status' => 1]);
                $message = 'Status berhasil diupdate';
                break;

            case 'sprint':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['print' => 1]);
                $message = 'Status data berhasil diprint';
                break;

            case 'unprint':
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['print' => 0]);
                $message = 'Status data batal diprint';
                break;

            case 'print':
                $ids = implode(',', $this->selectedIds);
                
                // Update print status
                Nota::whereIn('id', $this->selectedIds)
                    ->update(['print' => 1]);

                $this->reset(['selectedIds', 'bulkAction', 'selectAll']);

                return redirect()->route('nota.print.bulk', ['ids' => $ids]);

            default:
                $this->dispatch('alert', [
                    'type' => 'error',
                    'message' => 'Aksi tidak valid'
                ]);
                return;
        }

        // Reset setelah aksi
        $this->reset(['selectedIds', 'selectAll', 'bulkAction']);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => $message ?? 'Bulk action berhasil'
        ]);
    }

    public function toggleSelectAll()
    {
        $currentPageIds = $this->baseQuery()
            ->paginate($this->paginate)
            ->pluck('id')
            ->toArray();

        if (count(array_intersect($currentPageIds, $this->selectedIds)) === count($currentPageIds)) {
            $this->selectedIds = array_values(array_diff($this->selectedIds, $currentPageIds));
            $this->selectAll = false;
        } else {
            $this->selectedIds = array_values(array_unique(array_merge($this->selectedIds, $currentPageIds)));
            $this->selectAll = true;
        }
    }

    public function updatedSelectedIds()
    {
        $currentPageIds = $this->baseQuery()
            ->paginate($this->paginate)
            ->pluck('id')
            ->toArray();

        if (empty($currentPageIds)) {
            $this->selectAll = false;
        } else {
            $this->selectAll = count(array_intersect($currentPageIds, $this->selectedIds)) === count($currentPageIds);
        }
    }

    public function updatedBulkAction($value)
    {
        // Jika value kosong, skip
        if (empty($value)) {
            return;
        }

        // Validasi
        if (empty($this->selectedIds)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Pilih data terlebih dahulu'
            ]);
            $this->bulkAction = ''; // Reset
            return;
        }

        // Dispatch event untuk konfirmasi
        $this->dispatch('confirm-bulk-action', [
            'action' => $value
        ]);
    }

    public function printBulk(Request $request)
    {
        $ids = explode(',', $request->ids);
        $notas = Nota::whereIn('id', $ids)->get();

        return view('nota.print-bulk', compact('notas'));
    }

    public function confirmBulkAction()
    {
        if (!$this->bulkAction) return;

        if (empty($this->selectedIds)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Pilih data terlebih dahulu'
            ]);

            $this->reset('bulkAction');
            return;
        }

        $this->dispatch('confirm-bulk-action', [
            'action' => $this->bulkAction
        ]);
    }
        
}
