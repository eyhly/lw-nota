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
                <input type="text" class="form-control" wire:model="pembeli">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <textarea class="form-control" wire:model="alamat"></textarea>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-sm align-middle">
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
            @foreach ($details as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td><input type="text" class="form-control" wire:model="details.{{ $i }}.nama_barang"></td>
                    <td>
                        <div class="d-flex">
                            <input type="number" class="form-control me-1" wire:model="details.{{ $i }}.coly">
                            <input type="text" class="form-control" wire:model="details.{{ $i }}.satuan_coly">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <input type="number" class="form-control me-1" wire:model="details.{{ $i }}.qty_isi">
                            <input type="text" class="form-control" wire:model="details.{{ $i }}.nama_isi">
                        </div>
                    </td>
                    <td class="text-center">
                        {{ $details[$i]['coly'] * $details[$i]['qty_isi'] }}
                    </td>
                    <td><input type="number" class="form-control" wire:model="details.{{ $i }}.harga"></td>
                    <td><input type="number" class="form-control" wire:model="details.{{ $i }}.diskon"></td>
                    <td class="text-end">
                        {{ number_format(
                            ($details[$i]['harga'] * $details[$i]['coly'] * $details[$i]['qty_isi'])
                            * (1 - (($details[$i]['diskon'] ?? 0) / 100)),
                            0, ',', '.'
                        ) }}
                    </td>
                    <td class="text-center">
                        <button wire:click="removeDetail({{ $i }})" class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
            @endforeach

            {{-- form row baru --}}
            <tr>
                <td class="text-center">{{ count($details) + 1 }}</td>
                <td><input type="text" class="form-control" wire:model="formDetail.nama_barang"></td>
                <td>
                    <div class="d-flex">
                        <input type="number" class="form-control me-1" wire:model="formDetail.coly">
                        <input type="text" class="form-control" wire:model="formDetail.satuan_coly">
                    </div>
                </td>
                <td>
                    <div class="d-flex">
                        <input type="number" class="form-control me-1" wire:model="formDetail.qty_isi">
                        <input type="text" class="form-control" wire:model="formDetail.nama_isi">
                    </div>
                </td>
                <td class="text-center">
                    {{ $formDetail['coly'] * $formDetail['qty_isi'] }}
                </td>
                <td><input type="number" class="form-control" wire:model="formDetail.harga"></td>
                <td><input type="number" class="form-control" wire:model="formDetail.diskon"></td>
                <td class="text-end">
                    {{ number_format(
                        ($formDetail['harga'] * $formDetail['coly'] * $formDetail['qty_isi'])
                        * (1 - (($formDetail['diskon'] ?? 0) / 100)),
                        0, ',', '.'
                    ) }}
                </td>
                <td class="text-center">
                    <button wire:click="addDetail" class="btn btn-sm btn-primary">Tambah</button>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="7" class="text-right">Subtotal</th>
                <th class="text-right">{{ number_format($this->subtotal, 0, ',', '.') }}</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="7" class="text-right">Diskon</th>
                <th>
                    <div class="d-flex">
                        <input type="number" class="form-control me-1" wire:model.live="diskon_persen"> %
                        <input type="number" class="form-control ms-2" wire:model.live="diskon_rupiah">
                    </div>
                </th>
                <th></th>
            </tr>
            <tr>
                <th colspan="7" class="text-right">Total Harga</th>
                <th class="text-right fw-bold">{{ number_format($this->totalHarga, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-right">
        <button type="button" wire:click="store" class="btn btn-success">Simpan Nota</button>
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


