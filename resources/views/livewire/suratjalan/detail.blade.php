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
      </div><!-- /.container-fluid -->
    </section>

        <div class="card-body">
            <div class="row justify-content-between mb-3">
                <div class="col-md-8">
                    <div class="mb-2">
                        <strong>No Surat:</strong> {{ $suratjalan->no_surat }}
                    </div>                    
                    <div class="mb-2">
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($suratjalan->tanggal)->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-2">
                        <strong>Pembeli:</strong> {{ $suratjalan->pembeli }}
                    </div>
                </div>
            </div>
             
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Coly</th>
                        <th>Isi </th>
                        <th>Nama Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratjalan->detailsj as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->coly }}</td>
                            <td>{{ $detail->isi }}</td>
                            <td>{{ $detail->nama_barang }}</td>
                        </tr>                        

                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada detail barang</td>
                        </tr>
                    @endforelse

                    @if($suratjalan->detailsj->count() > 0)
                        <tr>
                            <td class="text-right" colspan="3"><strong>Total Coly:</strong></td>
                            <td><strong>{{ number_format($suratjalan->total_coly) }}</strong></td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>

            <a href="{{ route('suratjalan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>

</div>