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
            <div class="row justify-content-between mb-3">
                <div class="col-md-8">
                    <div class="mb-2">
                        <strong>No Nota:</strong> {{ $nota->no_nota }}
                    </div>                    
                    <div class="mb-2">
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($nota->tanggal)->format('d M Y') }}
                    </div>
                    <div class="mb-2">
                        <strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($nota->jatuh_tempo)->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-2">
                        <strong>Pembeli:</strong> {{ $nota->pembeli }}
                    </div>
                    <div class="mb-2">
                        <strong>Alamat:</strong> {{ $nota->alamat }}
                    </div>
                </div>
            </div>
             
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Coly</th>
                        <th>Qty </th>
                        <th>Total Qty</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($nota->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                            <td>{{ $detail->coly }} {{ $detail->satuan_coly }}</td>
                            <td>{{ $detail->qty_isi }} {{ $detail->nama_isi }}</td>
                            <td>{{ $detail->jumlah }} {{ $detail->satuan_coly }}</td>
                            <td>Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>
                                @if(is_array($detail->diskon))
                                    {{ implode(', ', $detail->diskon) }}
                                @else
                                    {{ $detail->diskon }}
                                @endif
                            </td>
                            <td>Rp{{ number_format($detail->total, 0, ',', '.') }}</td>
                        </tr>                                                

                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada detail barang</td>
                        </tr>
                    @endforelse

                    @if($nota->details->count() >  0)
                        <tr>
                            <td class="text-right text-bold" colSpan="7">Subtotal:</td>
                            <td>Rp{{ number_format($nota->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-right text-bold" colSpan="7">Diskon:</td>
                            <td>{{ $nota->diskon_persen }}% (Rp{{ number_format($nota->diskon_rupiah, 0, ',', '.') }})</td>
                        </tr>
                        <tr>
                            <td class="text-right text-bold" colSpan="7">Total Harga:</td>
                            <td>Rp{{ number_format($nota->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endif

                    
                </tbody>
            </table>

            <a href="{{ route('nota.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>

</div>