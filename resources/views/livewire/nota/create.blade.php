<div>
    <div class="content-wrapper" style="padding-right: 3rem; padding-left: 3rem;">
        <div class="mb-2">
            <a href="{{ route('nota.index') }}" class="page-back" style="font-size: 14px; color: #2563eb; text-decoration: none;">
                ← Kembali ke List Nota
            </a>
        </div>

        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><strong>{{ $title }}</strong></h1>
                </div>                
                {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">
                                    <i class="fas fa-home mr-1"></i>
                                    Dashboard</a></li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-plus mr-1"></i>
                                {{ $title }}
                            </li>
                        </ol>
                    </div> --}}
            </div>
        </section>

        <!-- Form Header Nota -->
        <div class="card p-4 mb-4">
            <div class="font-weight-bold h4">Informasi Nota</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2">
                        <label>No Nota</label>
                        <input type="text" id="no_nota" wire:model="no_nota" readonly class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" wire:model="tanggal">
                    </div>
                    <div class="mb-2">
                        <label>Jatuh Tempo</label>
                        <input type="date" class="form-control" wire:model="jt_tempo">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2">
                        <label>Pembeli</label>
                        <input type="text" class="form-control" wire:model="pembeli" placeholder="pembeli">
                    </div>
                    <div class="mb-2">
                        <label>Nama Toko</label>
                        <input type="text" class="form-control" wire:model="nama_toko" placeholder="nama toko">
                    </div>
                    <div class="mb-2">
                        <label>Alamat</label>
                        <input type="text" class="form-control" wire:model="alamat" placeholder="alamat" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Barang Baru (di Atas Tabel) -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="font-weight-bold h4">Tambah Barang</div>
                <div class="row">
                    <!-- Nama Barang -->
                    <div class="col-md-12 mb-2">
                        <label class="medium">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" wire:model="formDetail.nama_barang"
                            placeholder="Nama barang">
                    </div>
                </div>
                <div class="row">
                    <!-- Coly -->
                    <div class="col-md-4 mb-2">
                        <label class="medium">Coly</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control" wire:model="formDetail.coly" placeholder="0"
                                style="max-width: 70px;">
                            <input type="text" class="form-control" wire:model="formDetail.satuan_coly"
                                placeholder="Satuan">
                        </div>
                    </div>

                    <!-- Qty Isi -->
                    <div class="col-md-4 mb-2">
                        <label class="medium">Qty Isi</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control" wire:model="formDetail.qty_isi" placeholder="0"
                                style="max-width: 70px;">
                            <input type="text" class="form-control" wire:model="formDetail.nama_isi"
                                placeholder="Satuan">
                        </div>
                    </div>

                    <!-- Total Qty -->
                    <div class="col-md-4 mb-2">
                        <label class="medium">Total</label>
                        <input type="text" class="form-control form-control-sm text-center bg-light"
                            value="{{ $formDetail['coly'] * $formDetail['qty_isi'] }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <!-- Harga -->
                    <div class="col-md-7 mb-2">
                        <label class="medium">Harga</label>
                        <input type="number" class="form-control form-control-sm" wire:model="formDetail.harga"
                            placeholder="0">
                    </div>

                    <!-- Diskon -->
                    <div class="col-md-5 mb-2">
                        <label class="medium">Diskon (%)</label>
                        @foreach ((array) ($formDetail['diskon'] ?? []) as $d => $val)
                            <div class="input-group input-group-sm mb-1">
                                <input type="number" class="form-control"
                                    wire:model="formDetail.diskon.{{ $d }}" placeholder="%">
                                <div class="input-group-append">
                                    <button class="btn btn-danger btn-sm" type="button"
                                        wire:click="removeFormDiskon({{ $d }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <button class="btn btn-sm btn-success btn-block" type="button" wire:click="addFormDiskon">
                            <i class="fas fa-plus"></i> Diskon
                        </button>
                    </div>
                </div>

                <!-- Tombol Tambah -->
                <div class="col-md-12 mt-3 text-right" style="color: #495057;">
                    @php
                        $formDiskon = array_sum(array: (array) ($formDetail['diskon'] ?? []));
                        $subtotalItem =
                            $formDetail['harga'] *
                            $formDetail['coly'] *
                            $formDetail['qty_isi'] *
                            (1 - $formDiskon / 100);
                    @endphp
                    <span class="mr-3 h5">
                        <strong>Subtotal:</strong>
                        <span><strong> Rp {{ number_format($subtotalItem, 0, ',', '.') }}</strong></span>
                    </span>
                    <button wire:click="addDetail" type="button" class="btn btn-primary btn-sm" style="width: 250px">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Barang -->
        <div class="card py-3 px-4 mb-0">
            <div class="font-weight-bold h4">Tambah Barang</div>
            <table class="table table-bordered table-sm align-middle">
                <colgroup>
                    <col style="width: 40px">
                    <col style="width: 280px">
                    <col style="width: 140px">
                    <col style="width: 140px">
                    <col style="width: 90px">
                    <col style="width: 130px">
                    <col style="width: 100px">
                    <col style="width: 150px">
                    <col style="width: 100px">
                </colgroup>

                <thead class="table-secondary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Coly</th>
                        <th>Qty</th>
                        <th>Total Qty</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Sub Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $i => $item)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>

                            <!-- Nama Barang -->
                            <td>
                                @if ($editIndex === $i)
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model="editData.nama_barang">
                                @else
                                    {{ $item['nama_barang'] }}
                                @endif
                            </td>

                            <!-- Coly -->
                            <td>
                                @if ($editIndex === $i)
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" wire:model="editData.coly"
                                            style="max-width: 70px;">
                                        <input type="text" class="form-control" wire:model="editData.satuan_coly">
                                    </div>
                                @else
                                    {{ $item['coly'] }} {{ $item['satuan_coly'] }}
                                @endif
                            </td>

                            <!-- Qty Isi -->
                            <td>
                                @if ($editIndex === $i)
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" wire:model="editData.qty_isi"
                                            style="max-width: 70px;">
                                        <input type="text" class="form-control" wire:model="editData.nama_isi">
                                    </div>
                                @else
                                    {{ $item['qty_isi'] }} {{ $item['nama_isi'] }}
                                @endif
                            </td>

                            <!-- Total Qty -->
                            <td class="text-center">
                                {{ $editIndex === $i ? $editData['coly'] * $editData['qty_isi'] : $item['jumlah'] }}
                            </td>

                            <!-- Harga -->
                            <td>
                                @if ($editIndex === $i)
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model="editData.harga">
                                @else
                                    {{ number_format($item['harga'], 0, ',', '.') }}
                                @endif
                            </td>

                            <!-- Diskon -->
                            <td>
                                @if ($editIndex === $i)
                                    @foreach ((array) ($editData['diskon'] ?? []) as $d => $val)
                                        <div class="input-group input-group-sm mb-1">
                                            <input type="number" class="form-control"
                                                wire:model="editData.diskon.{{ $d }}" placeholder="%">
                                            <div class="input-group-append">
                                                <button class="btn btn-danger btn-sm" type="button"
                                                    wire:click="removeEditDiskon({{ $d }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <button class="btn btn-success btn-sm btn-block" type="button"
                                        wire:click="addEditDiskon">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    {{ implode(' + ', (array) ($item['diskon'] ?? [])) }}
                                @endif
                            </td>

                            <!-- Total -->
                            <td class="text-end">
                                {{ number_format($editIndex === $i ? $editData['total'] ?? 0 : $item['total'], 0, ',', '.') }}
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                @if ($editIndex === $i)
                                    <button type="button" wire:click="saveEdit" class="btn btn-success btn-sm mr-1"
                                        title="Simpan">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button type="button" wire:click="cancelEdit" class="btn btn-secondary btn-sm"
                                        title="Batal">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <button type="button" wire:click="startEdit({{ $i }})"
                                        class="btn btn-primary btn-sm mr-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" wire:click="removeDetail({{ $i }})"
                                        class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                Belum ada barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="7" class="text-right">Subtotal</th>
                        <th colspan="2" class="text-left">{{ number_format($this->subtotal, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-right">Diskon</th>
                        <th colspan="2">
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control form-control-sm text-end"
                                    style="width: 70px" wire:model.live="diskon_persen">
                                <span class="ml-1 text-muted">%</span>

                                <span class="mx-1 text-muted">-</span>

                                <span class="mr-1 text-muted">Rp</span>
                                <input type="number" class="form-control form-control-sm text-end"
                                    style="width: 110px" wire:model.live="diskon_rupiah">
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-right">Total Harga</th>
                        <th colspan="2" class="text-left fw-bold">
                            {{ number_format($this->totalHarga, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-right py-3">
            <button type="button" wire:click="store" class="btn btn-success">
                <i class="fas fa-save mr-1"></i> Simpan Nota
            </button>
        </div>

        @script
            <script>
                $wire.on('showSuccessAlert', (data) => {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Nota berhasil disimpan!",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('nota.index') }}";
                        }
                    });
                });
            </script>
        @endscript

    </div>
</div>
