<div>
    <div class="content-wrapper" style="padding: 2rem 3rem;">
        <!-- NAVIGASI KEMBALI -->
        <div class="mb-2">
            <a href="{{ route('nota.index') }}" class="page-back" style="font-size: 14px; color: #2563eb; text-decoration: none;">
                ← Kembali ke List Nota
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div style="font-size: 26px; font-weight: 700;">Detail Nota</div>

            <div class="d-flex">
                <a href="{{ route('nota.print.update', $nota->id) }}" 
                target="_blank" 
                class="btn btn-sm btn-warning mr-4">
                    <i class="fas fa-print mr-1"></i> Print
                </a>

                    <div class="text-center mr-4">
                        <button
                            type="button"
                            wire:click="toggleCek({{ $nota->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleCek({{ $nota->id }})"
                            class="btn btn-sm {{ $nota->cek ? 'btn-success' : 'btn-outline-danger' }}"
                            style="min-width: 90px;"
                        >
                            {{-- Cek --}}
                            <span wire:loading.remove wire:target="toggleCek({{ $nota->id }})">
                                @if($nota->cek)
                                    <i class="fas fa-check-circle mr-1"></i> Dicek
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Uncek
                                @endif
                            </span>

                            {{-- Loader --}}
                            <span wire:loading wire:target="toggleCek({{ $nota->id }})">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>

                    <div class="text-center mr-4">
                        <button
                            type="button"
                            wire:click="togglePrint({{ $nota->id }})"
                            wire:loading.attr="disabled"
                            wire:target="togglePrint({{ $nota->id }})"
                            class="btn btn-sm {{ $nota->print ? 'btn-success' : 'btn-outline-danger' }}"
                            style="min-width: 90px;"
                        >
                            {{-- Print --}}
                            <span wire:loading.remove wire:target="togglePrint({{ $nota->id }})">
                                @if($nota->print)
                                    <i class="fas fa-check-circle mr-1"></i> Diprint
                                @else
                                    <i class="fas fa-times-circle mr-1"></i> Unprint
                                @endif
                            </span>

                            {{-- Loader --}}
                            <span wire:loading wire:target="togglePrint({{ $nota->id }})">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>                   
            </div>
        </div>

    <!-- Form edit nota utama -->        
        <form wire:submit.prevent="updateNota">
            <div class="card mb-4" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                <div class="card-body">
                    <div class="font-weight-bold h5 mb-4">Informasi Nota</div>
                
                    <div class="row mb-3">
                        <div class="col-md-6">
                                <div class="d-flex mb-2">
                                    <label style="font-weight: 500; min-width: 130px;">Nomor Nota</label>
                                    <span class="mx-2">:</span>
                                    <input type="text" 
                                        wire:model="no_nota" 
                                        class="form-control form-control-sm mr-5" 
                                        readonly>
                                </div>

                                <div class="d-flex mb-2">
                                    <label style="font-weight: 500; min-width: 130px;">Tanggal Nota</label>
                                    <span class="mx-2">:</span>
                                    <input type="date" 
                                        wire:model="tanggal" 
                                        class="form-control form-control-sm mr-5">
                                </div>      

                                <div class="d-flex mb-2">
                                    <label style="font-weight: 500; min-width: 130px;">Jatuh Tempo</label>
                                    <span class="mx-2">:</span>
                                    <input type="date" 
                                        wire:model="jt_tempo" 
                                        class="form-control form-control-sm mr-5">
                                </div>                            
                        </div>                
                        <div class="col-md-6">                        
                                <div class="d-flex mb-2">
                                    <label style="font-weight: 500; min-width: 130px;">Nama Pembeli</label>
                                    <span class="mx-2">:</span>
                                    <input type="text" 
                                        wire:model="pembeli" 
                                        class="form-control form-control-sm mr-5">
                                </div>

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
                            </div> 
                    </div>                
                </div>
            </div>
    <!-- Tabel detail -->
    <div class="card py-3 px-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="font-weight-bold h5 mb-0">Daftar Barang</div>
                    
                @if(!$isAdding && $editIndex === null)
                    <button type="button" 
                        wire:click="startAdding" 
                        class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Item
                    </button>
                @endif
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif
        <table class="table table-bordered bg-white">

            <colgroup>
                <col style="width: 40px">      <!-- No -->
                <col style="width: 320px">     <!-- Nama Barang -->
                <col style="width: 100px">     <!-- Coly -->
                <col style="width: 100px">     <!-- Qty Isi -->
                <col style="width: 120px">      <!-- Subtotal Qty -->
                <col style="width: 130px">     <!-- Harga -->
                <col style="width: 100px">      <!-- Diskon -->
                <col style="width: 160px">     <!-- Total -->
                <col style="width: 90px">      <!-- Aksi -->
            </colgroup>

            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Coly</th>
                    <th>Qty</th>
                    <th>Total Qty</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>                
                @forelse ($nota->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                {{-- Nama Isi --}}
                                <td>
                                    @if ($editIndex === $index)
                                        <input type="text" class="form-control" wire:model="editData.nama_barang">
                                    @else
                                        {{ $detail->nama_barang }}
                                    @endif
                                </td>

                                {{-- Coly --}}
                                <td>
                                    @if ($editIndex === $index)
                                    <div class="d-flex">
                                        <input type="number" class="form-control" wire:model="editData.coly">
                                        <input type="text" class="form-control" wire:model="editData.satuan_coly">
                                    </div>
                                    @else
                                        {{ $detail->coly }} {{ $detail->satuan_coly }}
                                    @endif
                                </td>

                                {{-- Qty Isi --}}
                                <td>
                                    @if ($editIndex === $index)
                                    <div class="d-flex">
                                        <input type="number" class="form-control" wire:model="editData.qty_isi">
                                        <input type="text" class="form-control" wire:model="editData.nama_isi">
                                    </div>
                                        
                                    @else
                                        {{ $detail->qty_isi }} {{ $detail->nama_isi }}
                                    @endif
                                </td>                            

                                {{-- Jumlah --}}
                                <td>
                                    {{ $editIndex === $index ? ($editData['coly'] * $editData['qty_isi']) : $detail->jumlah . ' ' . $detail->satuan_coly }}
                                </td>

                                {{-- Harga --}}
                                <td>
                                    @if ($editIndex === $index)
                                        <input type="number" class="form-control" wire:model="editData.harga">
                                    @else
                                        {{ number_format($detail->harga) }}
                                    @endif
                                </td>

                                {{-- Diskon --}}
                                <td>
                                    @if ($editIndex === $index)

                                        @foreach ((array) ($editData['diskon'] ?? []) as $d => $val)
                                            <div class="d-flex align-items-center mb-1">
                                                <input type="number"
                                                    class="form-control me-1 mr-2"
                                                    wire:model="editData.diskon.{{ $d }}"
                                                    placeholder="%">

                                                <i class="fas fa-times text-danger"
                                                style="cursor:pointer"
                                                wire:click="removeEditDiskon({{ $d }})">
                                                </i>
                                            </div>
                                        @endforeach

                                        <button type="button" class="btn btn-sm btn-success d-flex align-items-center"
                                                wire:click="addEditDiskon">
                                            <span>+</span> Diskon
                                        </button>

                                    @else
                                        {{ implode(' + ', (array) ($detail->diskon ? explode(',', $detail->diskon) : [])) }}
                                    @endif
                                </td>

                                {{-- Total --}}
                                <td>
                                    @if ($editIndex === $index)
                                        @php
                                            $rowDiskon = array_sum((array) ($editData['diskon'] ?? []));
                                        @endphp
                                        {{ number_format($editData['total']) }}
                                    @else
                                        {{ number_format($detail->total) }}
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if ($editIndex === $index)

                                            {{-- Simpan --}}
                                            <button type="button"
                                                    wire:click="saveEdit"
                                                    class="btn btn-success btn-sm mr-2"
                                                    title="Simpan">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            {{-- Batal --}}
                                            <button type="button"
                                                    wire:click="cancelEdit"
                                                    class="btn btn-secondary btn-sm"
                                                    title="Batal">
                                                <i class="fas fa-times"></i>
                                            </button>

                                        @else                                    

                                            {{-- Edit --}}
                                            <button type="button"
                                                    wire:click="startEdit({{ $index }}, {{ $detail->id }})"
                                                    class="btn btn-primary btn-sm mr-2"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Hapus --}}
                                            <button type="button"
                                                    wire:click="deleteDetail({{ $detail->id }})"
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus"
                                                    onclick="return confirm('Yakin hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @if(!$isAdding)
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada detail barang</td>
                                </tr>
                            @endif
                        @endforelse

                        <!-- Row untuk Add New Item -->
                        @if($isAdding)
                            <tr class="table-info">
                                <td class="text-center">
                                    <i class="fas fa-plus-circle text-success"></i>
                                </td>

                                <!-- Nama Barang -->
                                <td>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           wire:model="newItem.nama_barang"
                                           placeholder="Nama barang">
                                    @error('newItem.nama_barang') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </td>

                                <!-- Coly -->
                                <td>
                                    <div class="d-flex">
                                        <input type="number" 
                                               class="form-control form-control-sm mr-1" 
                                               wire:model="newItem.coly"
                                               placeholder="Coly">
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               wire:model="newItem.satuan_coly"
                                               placeholder="Satuan">
                                    </div>
                                    @error('newItem.coly') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </td>

                                <!-- Qty Isi -->
                                <td>
                                    <div class="d-flex">
                                        <input type="number" 
                                               class="form-control form-control-sm mr-1" 
                                               wire:model="newItem.qty_isi"
                                               placeholder="Qty">
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               wire:model="newItem.nama_isi"
                                               placeholder="Satuan">
                                    </div>
                                    @error('newItem.qty_isi') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </td>

                                <!-- Jumlah -->
                                <td class="text-center">
                                    {{ $newItem['jumlah'] }} {{ $newItem['satuan_coly'] ?? '' }}
                                </td>

                                <!-- Harga -->
                                <td>
                                    <input type="number" 
                                           class="form-control form-control-sm" 
                                           wire:model="newItem.harga"
                                           placeholder="Harga">
                                    @error('newItem.harga') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </td>

                                <!-- Diskon -->
                                <td>
                                    @foreach ((array) ($newItem['diskon'] ?? []) as $d => $val)
                                        <div class="d-flex align-items-center mb-1">
                                            <input type="number"
                                                class="form-control form-control-sm me-1 mr-2"
                                                wire:model="newItem.diskon.{{ $d }}"
                                                placeholder="%">

                                            <i class="fas fa-times text-danger"
                                               style="cursor:pointer"
                                               wire:click="removeNewItemDiskon({{ $d }})">
                                            </i>
                                        </div>
                                    @endforeach

                                    <button type="button" 
                                            class="btn btn-sm btn-success d-flex align-items-center"
                                            wire:click="addNewItemDiskon">
                                        <span>+</span> Diskon
                                    </button>
                                </td>

                                <!-- Total -->
                                <td class="text-right">
                                    {{ number_format($newItem['total']) }}
                                </td>

                                <!-- Aksi -->
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Simpan -->
                                        <button type="button"
                                                wire:click="saveNewItem"
                                                class="btn btn-success btn-sm mr-2"
                                                title="Simpan">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <!-- Batal -->
                                        <button type="button"
                                                wire:click="cancelAdding"
                                                class="btn btn-secondary btn-sm"
                                                title="Batal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @if($nota->details->count() > 0)
                    <tr> 
                        <td class="text-right text-bold" colSpan="7">Subtotal:</td> 
                        <td colSpan="2">Rp{{ number_format($subtotal, 0, ',', '.') }}</td> 
                    </tr> 
                    <tr> 
                        <td class="text-right text-bold" colSpan="7">Diskon:</td> 
                        <td colSpan="2">{{ $diskon_persen }}% (Rp{{ number_format($diskon_rupiah, 0, ',', '.') }})</td> 
                    </tr> 
                    <tr> 
                        <td class="text-right text-bold" colSpan="7">Total Harga:</td> 
                        <td colSpan="2">Rp{{ number_format($total_harga, 0, ',', '.') }}</td> 
                    </tr>
                    
                    @endif               
            </tbody>

        </table>
    </div>
    <!-- FOOTER AKSI -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('nota.index') }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" 
                        class="btn btn-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updateNota">
                        <i class="fas fa-save mr-1"></i> Simpan Nota 
                    </span>
                    <span wire:loading wire:target="updateNota">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...
                    </span>
                </button>
            </div>
    </form>
</div>

@script
<script>
    $wire.on('notaUpdated', () => {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Nota berhasil diperbarui.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('nota.index') }}";
            }
        });
    });
</script>
@endscript

</div>