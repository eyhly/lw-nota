<div>
    <div class="content-wrapper">
    <div class="card">
        <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $title }}</h1>
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
                    <div class="col-md-8 mb-2">
                        <label>Pembeli</label>
                        <input type="text" wire:model="pembeli" class="form-control">
                    </div>
                    
                    <div class="col-md-8 mb-2">
                        <label>Alamat</label>
                        <textarea wire:model="alamat" class="form-control"></textarea>
                    </div>
                </div>
                
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('pdf.index', $nota->id) }}" target="_blank" class="btn btn-sm btn-warning" >
                    <i class="fas fa-print mr-1"></i>
                    Print
                </a>
            </div>
                    
    </div>

    <hr>

    {{-- Tabel detail --}}
  
    <table class="table table-striped table-bordered">
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
                                    <input type="number" class="form-control" wire:model="editData.diskon">
                                @else
                                    {{ $detail->diskon }}%
                                @endif
                            </td>

                            {{-- Total --}}
                            <td>
                                @if ($editIndex === $index)
                                    {{ number_format(($editData['coly'] * $editData['qty_isi'] * $editData['harga']) * (1 - ($editData['diskon']/100))) }}
                                @else
                                    {{ number_format($detail->total) }}
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                @if ($editIndex === $index)
                                    <button wire:click="saveEdit" class="btn btn-success btn-sm">Simpan</button>
                                    <button wire:click="cancelEdit" class="btn btn-secondary btn-sm">Batal</button>
                                @else
                                    <button wire:click="startEdit({{ $index }}, {{ $detail->id }})" class="btn btn-primary btn-sm">Edit</button>
                                    <button wire:click="deleteDetail({{ $detail->id }})" class="btn btn-danger btn-sm">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada detail barang</td>
                        </tr>
                    @endforelse
                @if($nota->details->count() > 0)
                <tr> 
                    <td class="text-right text-bold" colSpan="8">Subtotal:</td> 
                    <td >Rp{{ number_format($subtotal, 0, ',', '.') }}</td> 
                </tr> 
                <tr> 
                    <td class="text-right text-bold" colSpan="8">Diskon:</td> 
                    <td>{{ $diskon_persen }}% (Rp{{ number_format($diskon_rupiah, 0, ',', '.') }})</td> 
                </tr> 
                <tr> 
                    <td class="text-right text-bold" colSpan="8">Total Harga:</td> 
                    <td>Rp{{ number_format($total_harga, 0, ',', '.') }}</td> 
                </tr>
                @endif               
        </tbody>

    </table>    
    <button type="submit" class="btn btn-primary mt-3">Simpan Nota</button>
    </form>
</div>

    </div>
</div>

</div>