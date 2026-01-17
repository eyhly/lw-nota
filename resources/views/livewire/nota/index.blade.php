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
                                {{ $title }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">

            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <strong class="d-block mb-2">Filter Data</strong>
                        {{-- Button Filter --}}
                        <button type="button" wire:click="toggleFilter"
                            class="btn btn-sm btn-outline-secondary mr-1 d-inline-flex align-items-center">

                            @if ($showFilter)
                                Tutup Filter <i class="fas fa-sort-up fa-lg ml-1 mt-2"></i>
                            @else
                                Buka Filter <i class="fas fa-sort-down fa-lg ml-1 mb-2"></i>
                            @endif
                        </button>
                    </div>
                    @if ($showFilter) 
                        <div class="d-flex align-items-center flex-wrap">

                            {{-- Tahun --}}
                            <span>Tahun : &nbsp;</span>
                            <select wire:model.defer="tempYear" class="form-control form-control-sm w-auto mr-2">
                                @foreach (range(now()->year, now()->year - 5) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>

                            {{-- Bulan --}}
                            <span>Bulan : &nbsp;</span>
                            <select wire:model.defer="tempMonth" class="form-control form-control-sm w-auto mr-2">
                                <option value="">Semua Bulan</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}">
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Total Min --}}
                            <span>Min : &nbsp;</span>
                            <input type="number" wire:model.defer="tempTotalMin"
                                class="form-control form-control-sm w-auto mr-2" placeholder="Min Total">

                            {{-- Total Max --}}
                            <span>Max : &nbsp;</span>
                            <input type="number" wire:model.defer="tempTotalMax"
                                class="form-control form-control-sm w-auto mr-2" placeholder="Max Total">

                            {{-- Search --}}
                            <input type="text" wire:model.defer="tempSearch" class="form-control form-control-sm"
                                style="width: 250px" placeholder="Cari nama / status">
                        </div>
                        {{-- Spacer --}}
                        <div class="ml-auto mt-2 d-flex justify-content-end">

                            {{-- Reset --}}
                            <button type="button" wire:click="resetFilter"
                                class="btn btn-sm btn-outline-danger btn-light mr-2">
                                Reset
                            </button>

                            {{-- Cari --}}
                            <button type="button" wire:click="applyFilter" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a wire:navigate href="{{ route('nota.create') }}" class="btn btn-sm btn-primary"
                                data-toggle="modal">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        {{-- Info data --}}
                        <div class="text-muted medium">
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

                        {{-- Pagination --}}
                        <div>
                            {{ $nota->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                    <div class="table-responsive">

                        <form wire:submit.prevent="runBulkAction" wire:key="bulk-form-{{ count($selectedIds) }}">
                            @csrf

                            <table class="table table-hover">

                                <colgroup>
                                    <col style="width: 40px"> <!-- Check -->
                                    <col style="width: 60px"> <!-- No -->
                                    <col style="width: 220px"> <!-- Nama Toko -->
                                    <col style="width: 200px"> <!-- Tanggal -->
                                    <col style="width: 220px"> <!-- Tanggal -->
                                    <col style="width: 80px"> <!-- Print -->
                                    <col style="width: 80px"> <!-- Check -->
                                    <col style="width: 180px"> <!-- Aksi -->
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th><input type="checkbox" wire:click="toggleSelectAll"
                                                {{ $selectAll ? 'checked' : '' }} id="selectAllCheckbox"></th>
                                        <th>No</th>
                                        <th wire:click="sortBy('nama_toko')" style="cursor:pointer">
                                            Nama Toko
                                            @if ($sortField === 'nama_toko')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </th>
                                        <th wire:click="sortBy('tanggal')" style="cursor:pointer">
                                            Tanggal
                                            @if ($sortField === 'tanggal')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </th>
                                        <th wire:click="sortBy('total_harga')" style="cursor:pointer">
                                            Total Harga
                                            @if ($sortField === 'total_harga')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </th>
                                        <th wire:click="sortBy('print')" style="cursor:pointer">
                                            Print
                                            @if ($sortField === 'print')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </th>
                                        <th wire:click="sortBy('cek')" style="cursor:pointer">
                                            Cek
                                            @if ($sortField === 'cek')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort text-muted"></i>
                                            @endif
                                        </th>
                                        <th><i class="fas fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nota as $item)
                                        <tr style="cursor: pointer;"
                                            onclick="window.location='{{ route('nota.detail', $item->id) }}'">
                                            <td class="text-left" onclick="event.stopPropagation()">
                                                <input type="checkbox" value="{{ $item->id }}"
                                                    wire:model.live="selectedIds" @checked(in_array($item->id, $selectedIds))>
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_toko }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                            <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($item->print === 1)
                                                    <span class="text-success"><i
                                                            class="fas fa-check fs-5"></i></span>
                                                @else
                                                    <span class="text-danger"><i class="fas fa-times fs-5"></i></span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->cek === 1)
                                                    <span class="text-success"><i
                                                            class="fas fa-check fs-5"></i></span>
                                                @else
                                                    <span class="text-danger"><i class="fas fa-times fs-5"></i></span>
                                                @endif
                                            </td>
                                            <td>
                                                <a wire:navigate href="{{ route('nota.detail', $item->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye mr-1"></i>Detail
                                                </a>

                                                <!-- delete -->
                                                <button type="button" wire:click="confirm({{ $item->id }})"
                                                    class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                {{--  Bulk action --}}
                                <div class="d-flex align-items-center">
                                    <span class="text-muted mr-2">With selected:</span>

                                    <select wire:model.live="bulkAction"
                                        class="form-control form-control-sm w-auto mr-2" id="bulkActionSelect">
                                        <option value="">Pilih aksi</option>
                                        <option value="print">Cetak</option>
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
                initLivewireSwalHandlers();
            </script>
        @endscript

    </div>
</div>
