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
              List Surat Jalan</li>
              <li class="breadcrumb-item active">
                <i class="fas fa-eye mr-1"></i>
              {{ $title }}</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

        <div class="card-body">
            <form wire:submit.prevent="updateSurat">
                <div class="row justify-content-between mb-3">
                    <div class="col-md-7">
                        <div class="col-md-6 mb-2">
                            <label>No Surat</label>
                            <input type="text" wire:model="no_surat" class="form-control" readonly>
                        </div>                   
                        <div class="col-md-6 mb-2">
                            <label>Tanggal</label>
                            <input type="date" wire:model="tanggal" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="mb-2 col-md-8">
                            <label>Pembeli</label>
                            <input type="text" wire:model="pembeli" class="form-control">
                        </div>
                        <div class="mb-2 col-md-8">
                            <label>Alamat</label>
                            <input type="text" wire:model="alamat" class="form-control">
                        </div>
                        <div class=" d-flex mb-2 col-md-12">
                            <a href="{{ route('nota.create.fromSurat', $suratjalan->id) }}" class="btn btn-success mr-3">
                                <i class="fas fa-plus"></i> Buat Nota dari Surat Jalan
                            </a>
                            <!-- <div class="d-flex justify-content-end"> -->
                                <a href="{{ route('pdf.surat', $suratjalan->id) }}" target="_blank" class="btn btn-sm btn-warning" >
                                    <i class="fas fa-print mr-1"></i>
                                    Print
                                </a>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>            
             

            <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Coly</th>
            <th>Isi</th>
            <th>Nama Barang</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($suratjalan->detailsj as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>

                {{-- Kalau sedang edit --}}
                @if($editIndex === $index)
                    <td>
                        <div class="d-flex">
                            <input type="number" wire:model="editData.coly" class="form-control">
                            <input type="text" wire:model="editData.satuan_coly" class="form-control">
                        </div>
                    </td>                    
                    <td>
                        <div class="d-flex">
                            <input type="number" wire:model="editData.isi" class="form-control">
                            <input type="text" wire:model="editData.nama_isi" class="form-control">
                        </div>
                    </td>
                    <td>
                        <input type="text" wire:model="editData.nama_barang" class="form-control">
                    </td>
                    <td>
                        <button wire:click="saveEdit" class="btn btn-success btn-sm">Simpan</button>
                        <button wire:click="cancelEdit" class="btn btn-secondary btn-sm">Batal</button>
                    </td>
                @else
                    <td>{{ $detail->coly }} {{ $detail->satuan_coly }}</td>
                    <td>{{ $detail->isi }} {{ $detail->nama_isi }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td>
                        <button wire:click="startEdit({{ $index }}, {{ $detail->id }})" class="btn btn-primary btn-sm">Edit</button>
                        <button wire:click="deleteDetail({{ $detail->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada detail barang</td>
            </tr>
        @endforelse

        @if($suratjalan->detailsj->count() > 0)
            <tr>
                <td class="text-right" colspan="4"><strong>Total Coly :</strong></td>
                <td colspan="2"><strong>{{ $this->totalColy }}</strong></td>
            </tr>
        @endif
    </tbody>
</table>
            <button type="submit" class="btn btn-primary">Simpan Surat</button>

            <a href="{{ route('suratjalan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

</div>