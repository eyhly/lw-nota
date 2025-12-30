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
      font-size: 10px;
      margin: 5px;
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
      padding: 2px;  
    }

    tbody td {
      padding: 2px;
    }

    td {
      vertical-align: top;
      padding-bottom: 5px;
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
    font-size: 15px;
    font-weight: bold;
    }

    .kendaraan {
    font-size: 10px;
    }

    .coly {
    font-size: 10px;
    }

    .header{
      top: 0;
    }

    .grup{
      margin: auto;   
      width: 100%;
    }

    .gp1 {  
      text-align: right; 
      width: 50%;
    }

    .gp2 {
      text-align: left; 
      width: 50%;
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
      <td width="40%" style="text-align: center;">
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
        <th style="text-align: center;" width="2%">NO</th>
        <th style="text-align: center;" width="12%">COLY</th>
        <th style="text-align: center;" width="12%">ISI</th>
        <th style="padding-left: 5px; text-align: left;" width="47%">NAMA BARANG</th>
        <th style="padding-left: 5px; text-align: left;" width="27%">KETERANGAN</th>
      </tr>
    </thead>
    <tbody>
      @foreach($detailsj as $i => $d)
      <tr>
        <td style="text-align: center;">{{ ++$globalIndex }}</td>
        <td style="text-align: center;" >
            <div class="grup">
                <span class="gp1">{{ $d->coly }}</span>
                <span class="gp2">{{ $d->satuan_coly }}</span>
            </div>
        </td>
        <td style="text-align: center;">
          <div class="grup">
                <span class="gp1">{{ $d->qty_isi }}</span>
                <span class="gp2">{{ $d->nama_isi }}</span>
            </div>
        </td>
        <td style="padding-left: 5px; text-align: left;">{{ $d->nama_barang }}</td>
        <td style="padding-left: 5px; text-align: left;">{{ $d->keterangan }}</td>
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
        <td class="coly" width="10%" valign="top" style="text-align: left;">
          <b>Total Coly {{$suratjalan->total_coly}}</b>
        </td>  
      </tr>
      <br>
      <tr>            
        <td width="30%" valign="top" style="text-align: center;">
          Tanda Terima,<br><br><br><br>
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td width="30%" valign="top" style="text-align: center;">
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
