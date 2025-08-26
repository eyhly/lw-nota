<div>
    <div class="content-wrapper px-3">
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
              <li class="breadcrumb-item active">
                <i class="fas fa-plus mr-1"></i>
              {{ $title }}</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="mb-2">
                <label>No Surat Jalan</label>
                <input type="text" id="no_surat" wire:model="no_surat" readonly class="form-control">
            </div>
            <div class="mb-2">
                <label>Tanggal</label>
                <input type="date" class="form-control" wire:model="tanggal">
            </div>
            <div class="mb-2">
                <label>Kendaraan</label>
                <input type="text" class="form-control" wire:model="kendaraan">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label>Pembeli</label>
                <input type="text" class="form-control" wire:model="pembeli">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <textarea class="form-control" wire:model="alamat"></textarea>
            </div>
            <div class="mb-2">
                <label>No Kendaraan</label>
                <input class="form-control" wire:model="no_kendaraan"></input>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-sm align-middle">
        <thead class="table-secondary text-center">
            <tr>
                <th>No</th>
                <th>Coly</th>
                <th>Isi</th>
                <th>Nama Barang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detailsj as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item['coly'] }}</td>
                    <td>{{ $item['isi'] }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td class="text-center">
                        <button wire:click="removeDetail({{ $i }})" class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td class="text-center">{{ count($detailsj) + 1 }}</td>
                <td>
                  <input type="number" class="form-control me-1" wire:model="formDetail.coly">
                </td>
                <td>
                  <input type="text" class="form-control me-1" wire:model="formDetail.isi">
                </td>
                <td><input type="text" class="form-control" wire:model="formDetail.nama_barang"></td>
                <td class="text-center">
                    <button wire:click="addDetail" class="btn btn-sm btn-primary">Tambah</button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Coly</th>
                <th class="text-right">{{ ($this->total_coly) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-right">
        <button wire:click="store" class="btn btn-success">Simpan Surat</button>
    </div>
</div>
</div>
