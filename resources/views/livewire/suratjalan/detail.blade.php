<div>
    <div class="content-wrapper" style="padding: 2rem 3rem;">
        <!-- NAVIGASI KEMBALI -->
        <div class="mb-2">
            <a href="{{ route('suratjalan.index') }}" class="page-back" style="font-size: 14px; color: #2563eb; text-decoration: none;">
                ← Kembali ke Daftar Surat Jalan
            </a>
        </div>

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div style="font-size: 26px; font-weight: 700;">Detail Surat Jalan</div>

            <div class="d-flex">
                <a href="{{ route('nota.create.fromSurat', $suratjalan->id) }}" 
                   class="btn btn-success btn-sm mr-2" 
                   style="min-width: 110px;">
                    <i class="fas fa-plus mr-1"></i> Buat Nota
                </a>
                <a href="{{ route('suratjalan.print.update', $suratjalan->id) }}" 
                   target="_blank" 
                   class="btn btn-warning btn-sm text-dark mr-2"
                   style="min-width: 110px;">
                    <i class="fas fa-print mr-1"></i> Cetak
                </a>

                 <div class="text-center mr-2">
                        <button
                            type="button"
                            wire:click="toggleNota({{ $suratjalan->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleNota({{ $suratjalan->id }})"
                            class="btn btn-sm {{ $suratjalan->nota ? 'btn-success' : 'btn-outline-danger' }}"
                            style="min-width: 90px;"
                        >
                            {{-- Nota --}}
                            <span wire:loading.remove wire:target="toggleNota({{ $suratjalan->id }})">
                                @if($suratjalan->nota)
                                    <i class="fas fa-check-circle mr-1"></i> Nota
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Nota
                                @endif
                            </span>

                            {{-- Loader --}}
                            <span wire:loading wire:target="toggleNota({{ $suratjalan->id }})">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>

                    <div class="text-center mr-4">
                        <button
                            type="button"
                            wire:click="togglePrint({{ $suratjalan->id }})"
                            wire:loading.attr="disabled"
                            wire:target="togglePrint({{ $suratjalan->id }})"
                            class="btn btn-sm {{ $suratjalan->print ? 'btn-success' : 'btn-outline-danger' }}"
                            style="min-width: 90px;"
                        >
                            {{-- Print --}}
                            <span wire:loading.remove wire:target="togglePrint({{ $suratjalan->id }})">
                                @if($suratjalan->print)
                                    <i class="fas fa-check-circle mr-1"></i> Diprint
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Unprint
                                @endif
                            </span>

                            {{-- Loader --}}
                            <span wire:loading wire:target="togglePrint({{ $suratjalan->id }})">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>    
            </div>
        </div>

        <form wire:submit.prevent="updateSurat">
            <!-- INFORMASI SURAT JALAN -->
            <div class="card mb-4" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                <div class="card-body">
                    <div class="font-weight-bold h5 mb-4">Informasi Surat Jalan</div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <label style="font-weight: 500; min-width: 130px;">Nomor Surat Jalan</label>
                                <span class="mx-2">:</span>
                                <input type="text" 
                                    wire:model="no_surat" 
                                    class="form-control form-control-sm mr-5" 
                                    readonly>
                            </div>

                            <div class="d-flex mb-2">
                                <label style="font-weight: 500; min-width: 130px;">Tanggal Surat</label>
                                <span class="mx-2">:</span>
                                <input type="date" 
                                    wire:model="tanggal" 
                                    class="form-control form-control-sm mr-5">
                            </div>
                            
                            <div class="d-flex mb-2">
                                <label style="font-weight: 500; min-width: 130px;">Nama Kendaraan</label>
                                <span class="mx-2">:</span>
                                <input type="text" 
                                    wire:model="kendaraan" 
                                    class="form-control form-control-sm mr-5">
                            </div>
                        </div>                       
                        <div class="col-md-6">                        
                            <div class="d-flex mb-2">
                                <label style="font-weight: 500; min-width: 130px;">Nama Toko</label>
                                <span class="mx-2">:</span>
                                <input type="text" 
                                    wire:model="nama_toko" 
                                    class="form-control form-control-sm mr-5">
                            </div>

                            <div class="d-flex mb-2">
                                <label style="font-weight: 500; min-width: 130px;">Alamat</label>
                                <span class="mx-2">:</span>
                                <input type="text" 
                                    wire:model="alamat" 
                                    class="form-control form-control-sm mr-5">
                            </div>

                            <div class="d-flex mb-2">
                                <label style="font-weight: 500; min-width: 130px;">Nomor Kendaraan</label>
                                <span class="mx-2">:</span>
                                <input type="text" 
                                    wire:model="no_kendaraan" 
                                    class="form-control form-control-sm mr-5" 
                                    readonly>
                            </div>
                        </div>                       
                    </div>
                </div>
            </div>

            <!-- DAFTAR BARANG -->
            <div class="card py-3 px-4 mb-4">
              <div class="font-weight-bold h5">Daftar Barang</div>

                <table class="table table-bordered bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Coly / Kemasan</th>
                            <th>Jumlah Isi</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratjalan->detailsj as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                {{-- Mode Edit --}}
                                @if($editIndex === $index)
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" 
                                                   wire:model="editData.coly" 
                                                   class="form-control"
                                                   style="max-width: 70px;">
                                            <input type="text" 
                                                   wire:model="editData.satuan_coly" 
                                                   class="form-control"
                                                   placeholder="Satuan">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" 
                                                   wire:model="editData.qty_isi" 
                                                   class="form-control"
                                                   style="max-width: 70px;">
                                            <input type="text" 
                                                   wire:model="editData.nama_isi" 
                                                   class="form-control"
                                                   placeholder="Satuan">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               wire:model="editData.nama_barang" 
                                               class="form-control form-control-sm">
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                wire:click="saveEdit" 
                                                class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Simpan
                                        </button>
                                        <button type="button"
                                                wire:click="cancelEdit" 
                                                class="btn btn-sm btn-secondary">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </td>
                                @else
                                    {{-- Mode Normal --}}
                                    <td>{{ $detail->coly }} {{ $detail->satuan_coly }}</td>
                                    <td>{{ $detail->qty_isi }} {{ $detail->nama_isi }}</td>
                                    <td>{{ $detail->nama_barang }}</td>
                                    <td class="text-center">
                                        <button type="button"
                                                wire:click="startEdit({{ $index }}, {{ $detail->id }})" 
                                                class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button"
                                                wire:click="deleteDetail({{ $detail->id }})" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus data ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                    Tidak ada detail barang
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($suratjalan->detailsj->count() > 0)
                    <div class="fw-semibold mt-2" style="font-size: 16px;">
                        Total Coly : {{ $this->totalColy }}
                    </div>
                @endif
            </div>

            <!-- FOOTER AKSI -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('suratjalan.index') }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" 
                        class="btn btn-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updateSurat">
                        <i class="fas fa-save mr-1"></i> Simpan Surat Jalan
                    </span>
                    <span wire:loading wire:target="updateSurat">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...
                    </span>
                </button>
            </div>
        </form>

    </div>

    @script
    <script>
        // Success alert untuk update
        $wire.on('suratUpdated', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data surat jalan berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });

        // Alert untuk delete
        $wire.on('detailDeleted', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Detail barang berhasil dihapus.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
    @endscript
</div>