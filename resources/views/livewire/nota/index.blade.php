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
              <a wire:navigate href="{{ route('nota.create')}}" class="btn btn-sm btn-primary" data-toggle="modal">
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
                  Menampilkan {{ $nota->count() }} dari {{ $nota->total() }} data
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

              <div class="d-flex justify-content-between align-items-center" style="width: 550px;">
                    {{-- Search --}}
              <div style="width: 400px;">
                  <input
                      wire:model.live="search"
                      type="text"
                      class="form-control form-control-sm"
                      placeholder="Cari data..."
                  >
              </div>

                  {{-- Pagination atas --}}
              <div class="d-flex align-items-center mt-2">
                  {{ $nota->links('pagination::bootstrap-4') }}
              </div>
              </div>
          </div>

          <div class="table-responsive">            

          <form wire:submit.prevent="runBulkAction" wire:key="bulk-form-{{ count($selectedIds) }}">
            @csrf

            <table class="table table-hover">

              <colgroup>
                <col style="width: 40px">      <!-- Check -->
                <col style="width: 80px">     <!-- No -->
                <col style="width: 220px">     <!-- Pembeli -->
                <col style="width: 220px">     <!-- Tanggal -->
                <col style="width: 120px">      <!-- Print -->
                <col style="width: 120px">     <!-- Check -->
                <col style="width: 220px">      <!-- Aksi -->
              </colgroup>

              <thead>
                <tr>
                  <th><input type="checkbox" wire:click="toggleSelectAll" {{ $selectAll ? 'checked' : '' }} id="selectAllCheckbox"></th>
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
                  <th wire:click="sortBy('print')" style="cursor:pointer">
                        Print
                        @if ($sortField === 'print')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort text-muted"></i>
                        @endif
                  </th>
                  <th wire:click="sortBy('cek')" style="cursor:pointer">
                        Cek
                        @if ($sortField === 'cek')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort text-muted"></i>
                        @endif
                  </th>
                  <th><i class="fas fa-cog"></i></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($nota as $item)
                  <tr>
                    <td class="text-left">
                        <input
                            type="checkbox"
                            value="{{ $item->id }}"
                            wire:model.live="selectedIds"
                            @checked(in_array($item->id, $selectedIds))
                        >
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->pembeli }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                    <td>
                        @if($item->print === 1)
                            <span class="text-success"><i class="fas fa-check fs-5"></i></span>
                        @else
                            <span class="text-danger"><i class="fas fa-times fs-5"></i></span>
                        @endif
                    </td>
                    <td>
                        @if($item->cek === 1)
                            <span class="text-success"><i class="fas fa-check fs-5"></i></span>
                        @else
                            <span class="text-danger"><i class="fas fa-times fs-5"></i></span>
                        @endif
                    </td>                    
                    <td>
                      <a wire:navigate href="{{ route('nota.detail', $item->id) }}" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal">
                        <i class="fas fa-eye mr-1"></i>Detail
                      </a>
                      <!-- <a wire:navigate href="{{ route('nota.detail', $item->id) }}" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal">
                        <i class="fas fa-edit"></i>
                      </a> -->

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

                  <select
                     wire:model.live="bulkAction"                      
                      class="form-control form-control-sm w-auto mr-2"                      
                      id="bulkActionSelect"
                  >
                      <option value="">Pilih aksi</option>
                      <option value="sprint">Sudah Cetak</option>
                      <option value="unprint">Belum Cetak</option>
                      <option value="approve">Sudah Cek</option>
                      <option value="unapprove">Belum Cek</option>
                      <option value="delete">Hapus</option>
                  </select>
              </div>

              {{-- Pagination bawah --}}
              <div>
                  {{ $nota->links('pagination::bootstrap-4') }}
              </div>
          </div>
        </form>
          </div>
        </div>
        <!-- /.card-body -->        
      </div>

    </section>

    @include('livewire.nota.delete')

      <!-- close delete modal -->
    @script
    <script>
        // Event untuk close delete modal
        $wire.on('closeDeleteModal', () => {
            $('#deleteModal').modal('hide');
            Swal.fire({
                title: "Berhasil!",
                text: "Kamu Berhasil Menghapus Data!",
                icon: "success"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('nota.index') }}";
                }
            });
        });

        // Listen untuk alert event dan force uncheck select all
        $wire.on('alert', (event) => {
            const alertData = event[0] || event;

            const checkbox = document.getElementById('selectAllCheckbox');
            if (checkbox) {
                checkbox.checked = false;
            }
            
            // Uncheck semua individual checkbox
            document.querySelectorAll('input[type="checkbox"][wire\\:model\\.live="selectedIds"]').forEach(cb => {
                cb.checked = false;
            });

            // Tampilkan alert
            Swal.fire({
                title: alertData.type === 'success' ? 'Berhasil!' : 'Gagal!',
                text: alertData.message,
                icon: alertData.type,
            })
        });

        // Event untuk konfirmasi bulk action
        $wire.on('confirm-bulk-action', (data) => {
            // Ambil action dari data (bisa berupa object atau array)
            const action = data.action || data[0]?.action || 'aksi ini';
            
            Swal.fire({
                title: 'Konfirmasi',
                html: `Apakah ingin melakukan aksi <b>${action}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
            }).then((result) => {                

                if(!result.isConfirmed){
                  // Reset action jika dibatalkan
                  $wire.set('bulkAction', '');
                  return;
                }

                // Tutup modal konfirmasi
                Swal.close();

                // Tampilkan loader
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                //jalankan bulk action
                $wire.call('runBulkAction');
            });
        });
    </script>
    @endscript

  </div>
</div>
