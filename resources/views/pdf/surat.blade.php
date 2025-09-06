<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat PDF</title>
  <style>
    @font-face {
      font-family: 'MartianMono';
      src: url("{{ public_path('fonts/MartianMono-Regular.ttf') }}") format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    body {
      font-family: 'MartianMono', monospace;
      font-size: 20px;
      margin: 20px;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr {
      border-top: 1px solid black;
      border-bottom: 1px solid black;
    }

    thead th {
      padding: 4px;  
    }

    tbody td {
      padding: 4px;
    }

    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
    }

    .page-break {
      page-break-after: always;
    }

    .faktur {
    font-size: 28px;
    font-weight: bold;
    }

    .kendaraan {
    font-size: 15px;
    }
    
  </style>
</head>
<body>

@foreach($chunks as $chunkIndex => $detailsj)
  <!-- Header Nota -->
  <table>
    <tr>
      <td width="30%">
        <b>MENTARI JAYA</b><br>
        <b>SURABAYA</b>
      </td>
      <td width="40%" align="center">
        <p class="faktur"><u>Surat Jalan</u></p>
      </td>
      <td width="30%"></td>
    </tr>
  </table>
  <br>

  <table>
    <tr>
      <td width="15%">
        No Surat  <br>
        Tanggal <br>        
      </td>
      <td width="50%">
        : {{ $suratjalan->no_surat }}<br>
        : {{ \Carbon\Carbon::parse($suratjalan->tanggal)->format('d/m/Y') }}<br>
      </td>
      <td width="35%">
        Kepada Yth, <br>
        {{ $suratjalan->pembeli }} <br>
        {{ $suratjalan->alamat }}
      </td>
    </tr>
  </table>

  <table style="width: 100%; margin-bottom: 2px; margin-top:2px;">
    <tr class="kendaraan">
      <td style="text-align: left;">
        Kami kirimkan barang-barang tersebut dibawah ini dengan kendaraan 
        <b>{{ $suratjalan->kendaraan }}</b>
      </td>
      <td style="text-align: right; white-space: nowrap;">
        <b>No. {{ $suratjalan->no_kendaraan }}</b>
      </td>
    </tr>
  </table>

  <!-- Detail Barang -->
  <table>
    <thead>
      <tr>
        <th align="center" width="5%">NO</th>
        <th align="center" width="15%">COLY</th>
        <th align="center" width="15%">ISI</th>
        <th align="left" width="65%">NAMA BARANG</th>
      </tr>
    </thead>
    <tbody>
      @foreach($detailsj as $i => $d)
      <tr>
        <td align="center">{{ ($chunkIndex*10) + $i + 1 }}</td>
        <td align="center">
            <div class="d-flex justify-content-between w-100">
                <span class="text-end w-50">{{ $d->coly }}</span>
                <span class="text-start w-50">{{ $d->satuan_coly }}</span>
            </div>
        </td>
        <td align="center">{{ $d->isi }} {{ $d->nama_isi }}</td>
        <td align="left">{{ $d->nama_barang }}</td>
      </tr>
      @endforeach

      {{-- Tambah baris kosong supaya footer selalu turun --}}
      @for($k = count($detailsj); $k < 10; $k++)
      <tr>
        <td colspan="8">&nbsp;</td>
      </tr>
      @endfor
    </tbody>
  </table>

  <!-- Footer -->
  <div class="footer">
    <hr>
    <table>
      <tr>
        <td class="kendaraan" width="10%" valign="top" align="center">
          <b>Total Coly {{$suratjalan->total_coly}}</b>
        </td>  
      </tr>
      <tr>            
        <td width="30%" valign="top" align="center">
          Tanda Terima,<br><br><br><br>
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td width="30%" valign="top" align="center">
          Hormat Kami,<br><br><br><br>
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>      
      </tr>
    </table>
  </div>

  @if(!$loop->last)
    <div class="page-break"></div>
  @endif
@endforeach

</body>
</html>
