<div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><strong>{{ $title }}</strong></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">
              <i class="fas fa-home mr-1"></i>  
              Dashboard</a></li>
              <li class="breadcrumb-item active">
                <i class="fas fa-list mr-1"></i>
              {{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <div>              
              <a wire:navigate href="{{ route('suratjalan.create') }}" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createModal">
              <i class="fas fa-plus mr-1"></i>  
              Tambah Data</a>
            </div>
            <div class="btn-group dropleft">
              <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-print mr-1"></i>
                Print
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item text-success" href="#">
                <i class="fas fa-file-excel mr-1"></i>  
                Excel</a>
                <a class="dropdown-item text-danger" href="#">
                <i class="fas fa-file-pdf mr-1"></i>  
                PDF</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
              {{-- Info data --}}
              <div class="text-muted small">
                  Menampilkan {{ $suratjalan->count() }} dari {{ $suratjalan->total() }} data
              </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
              {{-- Jumlah tampil --}}
              <div class="d-flex align-items-center">
                  <span class="text-muted mr-2">Tampilkan</span>
                  <select wire:model.live="paginate" class="form-control form-control-sm w-auto">
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                  </select>
                  <span class="text-muted ml-2">data</span>
              </div>

              <div class="d-flex justify-content-between align-items-center" style="width: 350px;">
                    {{-- Search --}}
              <div style="width: 200px;">
                  <input
                      wire:model.live="search"
                      type="text"
                      class="form-control form-control-sm"
                      placeholder="Cari data..."
                  >
              </div>

                  {{-- Pagination atas --}}
              <div class="d-flex align-items-center mt-2">
                  {{ $suratjalan->links('pagination::bootstrap-4') }}
              </div>
              </div>
          </div>

          <div class="table-responsive">
            <form wire:submit.prevent="runBulkAction" wire:key="bulk-form-{{ count($selectedIds) }}">
              @csrf

              <table class="table table-hover">
                <thead>
                  <tr>
                      <th>
                          <input
                              type="checkbox"
                              wire:click="toggleSelectAll"
                              {{ $selectAll ? 'checked' : '' }}
                              id="selectAllCheckbox">
                      </th>

                      <th>No</th>

                      <th wire:click="sortBy('pembeli')" style="cursor:pointer">
                          Pembeli
                          @if ($sortField === 'pembeli')
                              <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                          @else
                              <i class="fas fa-sort text-muted"></i>
                          @endif
                      </th>

                      <th wire:click="sortBy('tanggal')" style="cursor:pointer">
                          Tanggal
                          @if ($sortField === 'tanggal')
                              <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                          @else
                              <i class="fas fa-sort text-muted"></i>
                          @endif
                      </th>

                      <th wire:click="sortBy('status')" style="cursor:pointer">
                          Status
                          @if ($sortField === 'status')
                              <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                          @else
                              <i class="fas fa-sort text-muted"></i>
                          @endif
                      </th>

                      <th><i class="fas fa-cog"></i></th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($suratjalan as $item)
                    <tr>
                      <td class="text-left">
                          <input
                              type="checkbox"
                              value="{{ $item->id }}"
                              wire:model.live="selectedIds"
                              @checked(in_array($item->id, $selectedIds))>
                      </td>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $item->pembeli }}</td>
                      <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                      <td class="text-left">
                          <label class="text-center switch">
                              <input 
                                  type="checkbox" 
                                  wire:click="toggleStatus({{ $item->id }})"
                                  @checked($item->status === 'sudah')>
                              <span class="slider">
                                  <span class="slider-text">
                                      @if ($item->status === 'sudah')
                                          <i class="fas fa-check fs-5"></i>
                                      @else
                                          <i class="text-danger fas fa-times fs-5"></i>
                                      @endif
                                  </span>
                              </span>
                          </label>
                      </td>
                      <td>
                        <a wire:navigate href="{{ route('suratjalan.detail', $item->id) }}" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal">
                          <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                        <button wire:navigate href="{{ route('suratjalan.edit', $item->id) }}" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal">
                          <i class="fas fa-edit"></i>
                        </button>

                        <!-- delete -->
                        <button wire:click="confirm({{$item->id}})" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              
              <div class="d-flex justify-content-between align-items-center mt-3">
              {{-- Bulk action --}}
              <div class="d-flex align-items-center">
                  <span class="text-muted mr-2">With selected:</span>

                  <select wire:model="bulkAction" class="form-control form-control-sm w-auto mr-2">
                      <option value="">Pilih aksi</option>
                      <option value="delete">Delete</option>
                      <option value="approve">Approve</option>
                      <option value="status">Status</option>
                      <option value="print">Print</option>
                  </select>

                  <button type="submit" class="btn btn-sm btn-primary">
                      Go
                  </button>
              </div>

              {{-- Pagination bawah --}}
              <div>
                  {{ $suratjalan->links('pagination::bootstrap-4') }}
              </div>
          </div>
            </form>
          </div>
        </div>
        <!-- /.card-body -->        
      </div>
    </section>

    @include('livewire.suratjalan.delete')

    <!-- close delete modal -->
    @script
    <script>
        $wire.on('closeDeleteModal', () => {
            $('#deleteModal').modal('hide');
            Swal.fire({
              title: "Berhasil!",
              text: "Kamu Berhasil Menghapus Data!",
              icon: "success"
            });
        });

        // Listen untuk alert event dan force uncheck select all
        $wire.on('alert', (event) => {
            const checkbox = document.getElementById('selectAllCheckbox');
            if (checkbox) {
                checkbox.checked = false;
            }
            
            // Uncheck semua individual checkbox
            document.querySelectorAll('input[type="checkbox"][wire\\:model\\.live="selectedIds"]').forEach(cb => {
                cb.checked = false;
            });
        });
    </script>
    @endscript
  </div>
</div>