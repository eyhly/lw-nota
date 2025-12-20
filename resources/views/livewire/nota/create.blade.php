<div>
    <div class="content-wrapper px-3">
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
                <input type="text" class="form-control" wire:model="pembeli" placeholder="pembeli">
            </div>
            <div class="mb-2">
                <label>Nama Toko</label>
                <input type="text" class="form-control" wire:model="nama_toko" placeholder="nama toko">
            </div>
            <div class="mb-2">
                <label>Alamat</label>
                <input type="text" class="form-control" wire:model="alamat" placeholder="alamat"/>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Form Input Barang Baru (di Atas Tabel) -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Nama Barang -->
                <div class="col-md-3 mb-2">
                    <label>Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           wire:model="formDetail.nama_barang" 
                           placeholder="Nama barang">
                </div>

                <!-- Coly -->
                <div class="col-md-2 mb-2">
                    <label>Coly <span class="text-danger">*</span></label>
                    <div class="d-flex">
                        <input type="number" 
                               class="form-control mr-1" 
                               wire:model="formDetail.coly"
                               placeholder="0">
                        <input type="text" 
                               class="form-control" 
                               wire:model="formDetail.satuan_coly" 
                               placeholder="Satuan">
                    </div>
                </div>

                <!-- Qty Isi -->
                <div class="col-md-2 mb-2">
                    <label>Qty Isi <span class="text-danger">*</span></label>
                    <div class="d-flex">
                        <input type="number" 
                               class="form-control mr-1" 
                               wire:model="formDetail.qty_isi"
                               placeholder="0">
                        <input type="text" 
                               class="form-control" 
                               wire:model="formDetail.nama_isi" 
                               placeholder="Satuan">
                    </div>
                </div>

                <!-- Total Qty (Readonly) -->
                <div class="col-md-1 mb-2">
                    <label>Total Qty</label>
                    <input type="text" 
                           class="form-control text-center" 
                           value="{{ $formDetail['coly'] * $formDetail['qty_isi'] }}" 
                           readonly>
                </div>

                <!-- Harga -->
                <div class="col-md-2 mb-2">
                    <label>Harga <span class="text-danger">*</span></label>
                    <input type="number" 
                           class="form-control" 
                           wire:model="formDetail.harga"
                           placeholder="0">
                </div>

                <!-- Diskon -->
                <div class="col-md-2 mb-2">
                    <label>Diskon (%)</label>
                    @foreach ((array) ($formDetail['diskon'] ?? []) as $d => $val)
                        <div class="d-flex align-items-center mb-1">
                            <input type="number"
                                   class="form-control mr-2"
                                   wire:model="formDetail.diskon.{{ $d }}"
                                   placeholder="%">
                            <i class="fas fa-times text-danger"
                               style="cursor:pointer;"
                               wire:click="removeFormDiskon({{ $d }})">
                            </i>
                        </div>
                    @endforeach

                    <button class="btn btn-sm btn-success btn-block mt-1"
                            wire:click="addFormDiskon">
                        <i class="fas fa-plus mr-1"></i> Diskon
                    </button>
                </div>
            </div>


    <table class="table table-bordered table-sm align-middle">

        <colgroup>
            <col style="width: 40px">      <!-- No -->
            <col style="width: 320px">     <!-- Nama Barang -->
            <col style="width: 140px">     <!-- Coly -->
            <col style="width: 140px">     <!-- Qty Isi -->
            <col style="width: 90px">      <!-- Subtotal Qty -->
            <col style="width: 140px">     <!-- Harga -->
            <col style="width: 90px">      <!-- Diskon -->
            <col style="width: 160px">     <!-- Total -->
            <col style="width: 90px">      <!-- Aksi -->
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
                    <td class="text-center">
                        {{ implode(' + ', (array) ($details[$i]['diskon'] ?? [])) }}
                    </td>
                    @php
                        $rowDiskon = array_sum((array) ($details[$i]['diskon'] ?? []));
                    @endphp

                    <td class="text-end">
                        {{ number_format(
                            ($details[$i]['harga'] * $details[$i]['coly'] * $details[$i]['qty_isi'])
                            * (1 - ($rowDiskon / 100)),
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
                <td><input type="text" class="form-control" wire:model="formDetail.nama_barang" placeholder="nama barang"></td>
                <td>
                    <div class="d-flex">
                        <input type="number" class="form-control me-1" wire:model="formDetail.coly">
                        <input type="text" class="form-control" wire:model="formDetail.satuan_coly" placeholder="coly">
                    </div>
                </td>
                <td>
                    <div class="d-flex">
                        <input type="number" class="form-control me-1" wire:model="formDetail.qty_isi">
                        <input type="text" class="form-control" wire:model="formDetail.nama_isi" placeholder="qty">
                    </div>
                </td>
                <td class="text-center">
                    {{ $formDetail['coly'] * $formDetail['qty_isi'] }}
                </td>
                <td><input type="number" class="form-control" wire:model="formDetail.harga"></td>
                <td>
                    @foreach ((array) ($formDetail['diskon'] ?? []) as $d => $val)
                        <div class="d-flex align-items-center mb-1">
                            <input type="number"
                                class="form-control me-1"
                                wire:model="formDetail.diskon.{{ $d }}"
                                placeholder="%">
                            <i class="fas fa-times text-danger ml-2"
                            style="cursor:pointer;"
                            wire:click="removeFormDiskon({{ $d }})">
                            </i>
                        </div>
                    @endforeach

                    <button class="btn btn-sm btn-success"
                            wire:click="addFormDiskon">
                        + Diskon
                    </button>
                </td>
                @php
                    $formDiskon = array_sum((array) ($formDetail['diskon'] ?? []));
                @endphp

                <td class="text-end">
                    {{ number_format(
                        ($formDetail['harga'] * $formDetail['coly'] * $formDetail['qty_isi'])
                        * (1 - ($formDiskon / 100)),
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
                <th colspan="6" class="text-right">Subtotal</th>
                <th colspan="2" class="text-right">{{ number_format($this->subtotal, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="6" class="text-right">Diskon</th>
                <th colspan="2">
                    <div class="d-flex align-items-center gap-1">

                        <input
                            type="number"
                            class="form-control form-control-sm text-end"
                            style="width: 70px"
                            wire:model.live="diskon_persen"
                        >
                        <span class="text-muted small">%</span>

                        <span class="mx-1 text-muted">-</span>

                        <span class="text-muted small">Rp</span>
                        <input
                            type="number"
                            class="form-control form-control-sm text-end"
                            style="width: 110px"
                            wire:model.live="diskon_rupiah"
                        >

                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="6" class="text-right">Total Harga</th>
                <th colspan="2" class="text-right fw-bold">{{ number_format($this->totalHarga, 0, ',', '.') }}</th>
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


