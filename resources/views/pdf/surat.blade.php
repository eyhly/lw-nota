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

    .coly {
    font-size: 18px;
    }

    .header{
      top: 0;
    }
    
  </style>
</head>
<body>

@php
    $globalIndex = 0;
@endphp

@foreach($chunks as $chunkIndex => $detailsj)
  <!-- Header Nota -->
  <table style="margin-top:0px; margin-bottom: 0x;" class="header">
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
        {{ $suratjalan->nama_toko }} <br>
        {{ $suratjalan->alamat }}
      </td>
    </tr>
  </table>

  <!-- <table style="width: 100%; margin-bottom: 2px; margin-top:2px;">
    <tr class="kendaraan">
      <td style="text-align: left;">
        Kami kirimkan barang-barang tersebut dibawah ini dengan kendaraan 
        <b>{{ $suratjalan->kendaraan }}</b>
      </td>
      <td style="text-align: right; white-space: nowrap;">
        <b>No. {{ $suratjalan->no_kendaraan }}</b>
      </td>
    </tr>
  </table> -->

  <!-- Detail Barang -->
  <table>
    <thead>
      <tr>
        <th align="center" width="5%">NO</th>
        <th align="center" width="15%">COLY</th>
        <th align="center" width="15%">ISI</th>
        <th align="left" width="45%">NAMA BARANG</th>
        <th align="left" width="20%">KETERANGAN</th>
      </tr>
    </thead>
    <tbody>
      @foreach($detailsj as $i => $d)
      <tr>
        <td align="center">{{ ++$globalIndex }}</td>
        <td align="center">
            <div class="d-flex justify-content-between w-80">
                <span class="text-end w-40">{{ $d->coly }}</span>
                <span class="text-start w-40">{{ $d->satuan_coly }}</span>
            </div>
        </td>
        <td align="center">{{ $d->qty_isi }} {{ $d->nama_isi }}</td>
        <td align="left">{{ $d->nama_barang }}</td>
        <td align="left">{{ $d->keterangan }}</td>
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
    @if($loop->last)
    <hr>
    <table>
      <tr>
        <td class="coly" width="10%" valign="top" align="left">
          <b>Total Coly {{$suratjalan->total_coly}}</b>
        </td>  
      </tr>
      <br>
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
    @endif
  </div>

  @if(!$loop->last)
    <div class="page-break"></div>
  @endif
@endforeach

</body>
</html>
