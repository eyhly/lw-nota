<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    public $paginate = '10';
    public $search = '';
    public $sortField = null;
    public $sortDirection = null;
    public bool $selectAll = false;
    public array $selectedIds = [];
    public string $bulkAction = '';

    public $no_surat, $tanggal, $nama_toko, $total_coly, $status, $suratjalan_id;
   
    public function render()
    {
        return view('livewire.suratjalan.index', [
            'title' => 'Data Surat Jalan',
            'suratjalan' => $this->baseQuery()->paginate($this->paginate),
        ]);
    }

    private function baseQuery()
    {
        $query = SuratJalan::where(function ($q) {
            $q->where('nama_toko', 'like', '%' . $this->search . '%')
            ->orWhere('status', 'like', '%' . $this->search . '%');
        });

        if ($this->sortField && in_array($this->sortDirection, ['asc', 'desc'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // DEFAULT: ID terbaru
            $query->orderBy('id', 'desc');
        }

        return $query;
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
    
    public function confirm($id)
    {
        $suratjalan = SuratJalan::findOrFail($id);

        $this->nama_toko = $suratjalan->nama_toko;
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
                SuratJalan::whereIn('id', $this->selectedIds)->delete();
                $message = 'Data berhasil dihapus';
                break;

            case 'approve':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['nota' => 1]);
                $message = 'Data berhasil dicek';
                break;

            case 'unapprove':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['nota' => 0]);
                $message = 'Data batal dicek';
                break;

            case 'status':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['status' => 1]);
                $message = 'Status berhasil diupdate';
                break;

            case 'sprint':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['print' => 1]);
                $message = 'Status data berhasil diprint';
                break;

            case 'unprint':
                SuratJalan::whereIn('id', $this->selectedIds)
                    ->update(['print' => 0]);
                $message = 'Status data batal diprint';
                break;

            case 'print':
                $ids = implode(',', $this->selectedIds);
                
                // Update print status
                SuratJalan::whereIn('id', $this->selectedIds)
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
        $notas = SuratJalan::whereIn('id', $ids)->get();

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