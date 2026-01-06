<div>
    <div class="content-wrapper" style="padding-right: 3rem; padding-left: 3rem;">
        <div class="mb-2">
            <a href="{{ route('suratjalan.index') }}" class="page-back" style="font-size: 14px; color: #2563eb; text-decoration: none;">
                ← Kembali ke Daftar Surat Jalan
            </a>
        </div>
    
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><strong>{{ $title }}</strong></h1>
                </div>
                <!-- <div class="col-sm-6">
                    <a href="{{ route('suratjalan.index') }}" class="btn btn-secondary mr-2 float-sm-right">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div> -->
            </div>
        </section>

        <!-- Form Header Surat Jalan -->
        <div class="card p-4 mb-4">
            <div class="font-weight-bold h4">Informasi Surat Jalan</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2">
                        <label>No Surat Jalan</label>
                        <input type="text" id="no_surat" wire:model="no_surat" readonly class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" wire:model="tanggal">
                    </div>                    
                </div>
                <div class="col-md-6">
                    <div class="mb-2">
                        <label>Nama Toko</label>
                        <input type="text" class="form-control" wire:model="nama_toko" placeholder="Nama Toko">
                    </div>
                    <div class="mb-2">
                        <label>Alamat</label>
                        <input type="text" class="form-control" wire:model="alamat" placeholder="Alamat">
                    </div>                    
                </div>
            </div>
        </div>

        <!-- Form Input Barang Baru (di Atas Tabel) -->
        <form wire:submit.prevent="addDetail">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="font-weight-bold h4">Tambah Barang</div>
                    
                    <div class="row">
                        <!-- Nama Barang -->
                        <div class="col-md-12 mb-2">
                            <label class="medium">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" 
                                id="nama-barang"
                                autofocus
                                class="form-control form-control-sm" 
                                wire:model.live="formDetail.nama_barang"
                                placeholder="Nama barang">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Coly -->
                        <div class="col-md-6 mb-2">
                            <label class="medium">Coly</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" 
                                    class="form-control mr-2" 
                                    wire:model.live="formDetail.coly"
                                    placeholder="0"
                                    style="max-width: 70px;">
                                <input type="text" 
                                    class="form-control" 
                                    wire:model="formDetail.satuan_coly" 
                                    placeholder="Satuan">
                            </div>
                        </div>

                        <!-- Isi -->
                        <div class="col-md-6 mb-2">
                            <label class="medium">Isi</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01"
                                    class="form-control mr-2" 
                                    wire:model.live="formDetail.qty_isi"
                                    placeholder="0"
                                    style="max-width: 70px;">
                                <input type="text" 
                                    class="form-control" 
                                    wire:model="formDetail.nama_isi"
                                    placeholder="Satuan">
                            </div>
                        </div>

                        <!-- Total (Readonly) -->
                        <!-- <div class="col-md-2 mb-2">
                            <label class="medium">Total</label>
                            <input type="text" 
                                class="form-control form-control-sm text-center bg-light"
                                value="{{ $this->formTotal }}"
                                readonly>

                        </div> -->
                    </div>

                    <div class="row">
                        <!-- Keterangan -->
                        <div class="col-md-12 mb-2">
                            <label class="medium">Keterangan</label>
                            <input type="text"                                
                                class="form-control form-control-sm" 
                                wire:model="formDetail.keterangan"
                                placeholder="Keterangan (Opsional)">
                        </div>
                    </div>

                    <!-- Tombol Tambah -->
                    <div class="mt-3 text-right">
                        <button type="submit"
                                class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Tabel Daftar Barang -->
        <div class="card py-3 px-4 mb-0">
            <div class="font-weight-bold h4">Daftar Barang</div>
            <table class="table table-bordered table-sm align-middle">
                <colgroup>
                    <col style="width: 5%">   <!-- No -->
                    <col style="width: 10%">  <!-- Coly -->
                    <col style="width: 10%">  <!-- Isi -->
                    <col style="width: 45%">  <!-- Nama Barang -->
                    <col style="width: 20%">  <!-- Keterangan -->
                    <col style="width: 10%">  <!-- Aksi -->
                </colgroup>

                <thead class="table-secondary text-center">
                    <tr>
                        <th>No</th>
                        <th>Coly</th>
                        <th>Isi</th>
                        <th>Nama Barang</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detailsj as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>

                            
                            <!-- Coly -->
                            <td>
                                @if ($editIndex === $i)
                                <div class="d-flex flex-column gap-1">
                                    <input type="number" step="0.01"
                                    class="form-control mb-2" 
                                    wire:model="editData.coly"
                                    >
                                    <input type="text" 
                                    class="form-control" 
                                    wire:model="editData.satuan_coly">
                                </div>
                                @else
                                {{ $item['coly'] }} {{ $item['satuan_coly'] }}
                                @endif
                            </td>
                            
                            <!-- Isi -->
                            <td>
                                @if ($editIndex === $i)
                                <div class="d-flex flex-column gap-1">
                                    <input type="number" step="0.01"
                                    class="form-control mb-2" 
                                    wire:model="editData.qty_isi"
                                    >
                                    <input type="text" 
                                    class="form-control" 
                                    wire:model="editData.nama_isi">
                                </div>
                                @else
                                {{ $item['qty_isi'] }} {{ $item['nama_isi'] }}
                                @endif
                            </td>
                            
                            <!-- Nama Barang -->
                            <td>
                                @if ($editIndex === $i)
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           wire:model="editData.nama_barang">
                                @else
                                    {{ $item['nama_barang'] }}
                                @endif
                            </td>
                            
                            <!-- Keterangan -->
                            <td>
                                @if ($editIndex === $i)
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           wire:model="editData.keterangan">
                                @else
                                    {{ $item['keterangan'] }}
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                @if ($editIndex === $i)
                                    <button type="button"
                                            wire:click="saveEdit"
                                            class="btn btn-success btn-sm mr-1"
                                            title="Simpan">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button type="button"
                                            wire:click="cancelEdit"
                                            class="btn btn-secondary btn-sm"
                                            title="Batal">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <button type="button"
                                            wire:click="startEdit({{ $i }})"
                                            class="btn btn-primary btn-sm mr-1"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button"
                                            wire:click="removeDetail({{ $i }})"
                                            class="btn btn-danger btn-sm"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                Belum ada barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="6" class="text-left" style="font-size: medium;">Total Coly : {{ $this->total_coly }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-right py-3">
            <button type="button"
                    wire:click="store"
                    class="btn btn-success">
                <i class="fas fa-save mr-1"></i> Simpan Surat Jalan
            </button>
        </div>

        @script
        <script>
            // Success Alert
             initLivewireSwalHandlers({
                redirectUrl: "{{ route('suratjalan.index') }}"
            });

            window.addEventListener('focus-nama-barang', () => {
                setTimeout(() => {
                    document.getElementById('nama-barang')?.focus();
                }, 50);
            });
        </script>
        @endscript

    </div>
</div>