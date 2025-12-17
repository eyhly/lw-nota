<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sortField = 'tanggal';
    public $sortDirection = 'asc';
    public bool $selectAll = false;
    public array $selectedIds = [];
    public string $bulkAction = '';

    public $no_surat, $tanggal, $pembeli, $kendaraan, $no_kendaraan, $total_coly, $status, $suratjalan_id;
   
    public function render()
    {
        $suratjalan = SuratJalan::where(function ($q) {
                $q->where('pembeli', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->paginate);

        return view('livewire.suratjalan.index', [
            'title' => 'Data Surat Jalan',
            'suratjalan' => $suratjalan,
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
    
    public function confirm($id)
    {
        $suratjalan = SuratJalan::findOrFail($id);

        $this->pembeli = $suratjalan->pembeli;
        $this->tanggal = $suratjalan->tanggal;
        $this->suratjalan_id = $suratjalan->id;
    }

    public function destroy($id)
    {
        $suratjalan = SuratJalan::findOrFail($id);
        $suratjalan->delete();
        $this->dispatch('closeDeleteModal'); 
    }

    public function toggleStatus($id)
    {
        $item = SuratJalan::find($id);

        if ($item) {
            $item->status = $item->status == 'sudah' ? 'belum' : 'sudah';
            $item->save();
        }
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
                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]);
                break;

            case 'approve':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['status' => 'sudah']);
                $this->dispatch('alert', [
                    'type' => 'success',
                    'message' => 'Status berhasil diupdate'
                ]);
                break;

            case 'print':
                $ids = implode(',', $this->selectedIds);
                
                // reset supaya tidak ke-trigger ulang
                $this->reset(['selectedIds', 'selectAll', 'bulkAction']);

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
        $this->reset(['selectedIds', 'selectAll', 'bulkAction']);
    }

    // Method untuk toggle select all
    public function toggleSelectAll()
    {
        // Ambil semua ID dari halaman saat ini
        $currentPageIds = SuratJalan::where(function ($q) {
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
}