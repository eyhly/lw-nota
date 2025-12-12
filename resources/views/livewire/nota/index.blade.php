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
              <button wire:navigate href="{{ route('nota.create')}}" class="btn btn-sm btn-primary" data-toggle="modal">
              <i class="fas fa-plus mr-1"></i>  
              Tambah Data</button>
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
          <div class="mb-3 d-flex justify-content-between">
            <div class="col-2">
              <select wire:model.live="paginate" class="form-control">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <div class="col-6">
              <input wire:model.live="search" type="text" placeholder="Masukkan Kata Kunci" class="form-control">
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Action</th>
                  <th>No</th>
                  <th>Pembeli</th>
                  <th>Tanggal</th>
                  <th>Print</th>
                  <th>Checking</th>
                  <th><i class="fas fa-cog"></i></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($nota as $item)
                  <tr>
                    <td class="text-left">
                        <button                             
                            class="btn btn-sm bg-transparent border-0 p-0"
                        >
                            @if($item->print == 1)
                                <i class="far fa-check-square text-success fs-5"></i>
                            @else
                                <i class="far fa-square fs-5"></i>
                            @endif
                        </button>
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
                    <td class="text-left">
                        <label class="text-center switch">
                            <input 
                                type="checkbox" 
                                wire:click="toggleCek({{ $item->id }})"
                                @checked($item->cek == 1)
                            >
                            <span class="slider">
                                <span class="slider-text">
                                    @if ($item->cek)
                                        <i class="fas fa-check fs-5"></i>
                                    @else
                                        <i class="text-danger fas fa-times fs-5"></i>
                                    @endif
                                </span>
                            </span>
                        </label>
                    </td>
                    <td>
                      <a wire:navigate href="{{ route('nota.detail', $item->id) }}" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal">
                        <i class="fas fa-eye mr-1"></i>Detail
                      </a>
                      <a wire:navigate href="{{ route('nota.detail', $item->id) }}" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal">
                        <i class="fas fa-edit"></i>
                      </a>

                      <!-- delete -->
                      <button wire:click="confirm({{$item->id}})" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{ $nota->links()}}
          </div>
        </div>
        <!-- /.card-body -->        
      </div>

    </section>

    @include('livewire.nota.delete')

      <!-- close delete modal -->
    @script
    <script>
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
        });;
        });
    </script>
    @endscript

  </div>
</div>
