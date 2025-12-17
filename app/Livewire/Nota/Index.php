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
    public bool $selectAll = false;
    public array $selectedIds = [];
    public string $bulkAction = '';
    public $sortField = 'tanggal';
    public $sortDirection = 'asc';

    public $no_nota,$pembeli,$tanggal,$subtotal,$diskon_persen,$diskon_rupiah,$total_harga,$total_coly,$jt_tempo,$nota_id;
   
    public function render()
    {
        return view('livewire.nota.index', [
            'title' => 'List Nota',
            'nota' => Nota::where(function ($q) {
                    $q->where('pembeli', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->paginate),
        ]);
    }

    public function updatingPaginate()
    {
        $this->resetPage();
        $this->reset(['selectedIds', 'selectAll']);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
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
        $this->reset([
            'selectedIds',
            'selectAll',
            'bulkAction',
        ]);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Bulk action berhasil'
        ]);
    }

    // Method untuk toggle select all
    public function toggleSelectAll()
    {
        // Ambil semua ID dari halaman saat ini
        $currentPageIds = Nota::where(function ($q) {
                $q->where('pembeli', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->paginate)
            ->pluck('id')
            ->toArray();

        // Toggle: jika sudah ada yang terselect, hapus semua. Jika belum, select semua
        if (count(array_intersect($currentPageIds, $this->selectedIds)) === count($currentPageIds)) {
            // Unselect semua di halaman ini
            $this->selectedIds = array_values(array_diff($this->selectedIds, $currentPageIds));
            $this->selectAll = false;
        } else {
            // Select semua di halaman ini (merge dengan yang sudah ada)
            $this->selectedIds = array_values(array_unique(array_merge($this->selectedIds, $currentPageIds)));
            $this->selectAll = true;
        }
    }

    // Update selectAll status ketika individual checkbox berubah
    public function updatedSelectedIds()
    {
        $currentPageIds = SuratJalan::where(function ($q) {
                $q->where('pembeli', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->paginate)
            ->pluck('id')
            ->toArray();

        // Check apakah semua ID di halaman ini sudah dicentang
        if (empty($currentPageIds)) {
            $this->selectAll = false;
        } else {
            $this->selectAll = count(array_intersect($currentPageIds, $this->selectedIds)) === count($currentPageIds);
        }
    }

    public function printBulk(Request $request)
    {
        $ids = explode(',', $request->ids);
        $notas = Nota::whereIn('id', $ids)->get();

        return view('nota.print-bulk', compact('notas'));
    }


        
}
