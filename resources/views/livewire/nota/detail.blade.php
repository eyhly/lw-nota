<div>
    <div class="content-wrapper">
    <div class="card">
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
              <li class="breadcrumb-item">
                <i class="fas fa-list mr-1"></i>
              List Nota</li>
              <li class="breadcrumb-item active">
                <i class="fas fa-eye mr-1"></i>
              {{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

        <div class="card-body">

    {{-- Form edit nota utama --}}
    <div class="mb-4">        
        <form wire:submit.prevent="updateNota">
            <div class="row justify-content-between mb-3">
                <div class="col-md-7">
                    <div class="col-md-6 mb-2">
                        <label>No Nota</label>
                        <input type="text" wire:model="no_nota" class="form-control" readonly>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Tanggal</label>
                        <input type="date" wire:model="tanggal" class="form-control">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Jatuh Tempo</label>
                        <input type="date" wire:model="jt_tempo" class="form-control">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label>Pembeli</label>
                            <input type="text" wire:model="pembeli" class="form-control">
                        </div>
                        <div class="col-md-8 mb-2">
                            <label>Nama Toko</label>
                            <input type="text" wire:model="nama_toko" class="form-control">
                        </div>
                        
                        <div class="col-md-8 mb-2">
                            <label>Alamat</label>
                            <input type="text" wire:model="alamat" class="form-control"/>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('nota.print.update', $nota->id) }}" 
                target="_blank" 
                class="btn btn-sm btn-warning mr-4">
                    <i class="fas fa-print mr-1"></i> Print
                </a>

                    <div class="d-flex align-items-center text-center mr-4">
                        <input
                            type="checkbox"
                            wire:click="toggleCek({{ $nota->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleCek({{ $nota->id }})"
                            @checked($nota->cek == 1)
                        >

                        <span class="ml-2">
                            Cek
                        </span>

                        {{-- Loader --}}
                        <span class="ml-2" wire:loading wire:target="toggleCek({{ $nota->id }})">
                            <i class="fas fa-spinner fa-spin text-primary"></i>
                        </span>
                    </div>

                    <div class="d-flex align-items-center text-center mr-4">
                        <input
                            type="checkbox"
                            wire:click="togglePrint({{ $nota->id }})"
                            wire:loading.attr="disabled"
                            wire:target="togglePrint({{ $nota->id }})"
                            @checked($nota->print == 1)
                        >

                        <span class="ml-2">
                            Print
                        </span>

                        {{-- Loader --}}
                        <span class="ml-2" wire:loading wire:target="togglePrint({{ $nota->id }})">
                            <i class="fas fa-spinner fa-spin text-primary"></i>
                        </span>
                    </div>

            </div>
                    
    </div>

    <hr>

    {{-- Tabel detail --}}
  
    <table class="table table-striped table-bordered uppercase">

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

        <thead>
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

                                    <button class="btn btn-sm btn-success d-flex align-items-center"
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
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada detail barang</td>
                        </tr>
                    @endforelse
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
    <button wire:click="updateNota" type="submit" class="btn btn-primary mt-3">Simpan Nota</button>
    </form>
</div>

    </div>
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