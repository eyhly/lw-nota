<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Nota PDF</title>
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
      margin: 3px;
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
    font-size: 10px;
    font-weight: bold;
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

@foreach($chunks as $chunkIndex => $details)
  <!-- Header Nota -->
  <table>
    <tr>
      <td width="30%">
        <b>MENTARI JAYA</b><br>
        <b>SURABAYA</b>
      </td>
      <td width="40%" style="text-align: center;">
        <p class="faktur"><u>FAKTUR</u></p>
      </td>
      <td width="30%"></td>
    </tr>
  </table>
  <br>

  <table>
    <tr>
      <td width="15%">
        No Nota  <br>
        Tanggal <br>
        Jatuh Tempo 
      </td>
      <td width="50%">
        : {{ $nota->no_nota }}<br>
        : {{ \Carbon\Carbon::parse($nota->tanggal)->format('d/m/Y') }}<br>
        : {{ \Carbon\Carbon::parse($nota->jt_tempo)->format('d/m/Y') }}
      </td>
      <td width="35%">
        Kepada Yth, <br>
        {{ $nota->nama_toko }}<br>
        @if(!empty($nota->pembeli))
            {{ $nota->pembeli }}<br>
        @endif
        {{ $nota->alamat }}
      </td>
    </tr>
  </table>
  <br>

  <!-- Detail Barang -->
  <table>
    <thead>
      <tr>
        <th style="text-align: center;">NO</th>
        <th style="text-align: left;">NAMA BARANG</th>
        <th style="text-align: center;">COLY</th>
        <th style="text-align: center;">ISI</th>
        <th style="text-align: center;">TOTAL QTY</th>
        <th style="text-align: right;">HARGA</th>
        <th style="text-align: center;">% DISC</th>
        <th style="text-align: right; padding-right: 10px;">SUBTOTAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach($details as $i => $d)
      <tr>
        <td style="text-align: center;">{{ ++$globalIndex }}</td>
        <td style="text-align: left;">{{ $d->nama_barang }}</td>
        <td style="text-align: center;">
            <div class="d-flex justify-content-between w-100">
                <span class="text-end w-50">{{ $d->coly }}</span>
                <span class="text-start w-50">{{ $d->satuan_coly }}</span>
            </div>
        </td>
        <td style="text-align: center;">{{ $d->qty_isi }} {{ $d->nama_isi }}</td>
        <td style="text-align: center;">{{ $d->jumlah }} {{ $d->nama_isi }}</td>
        <td style="text-align: right;">{{ number_format($d->harga,0,',','.') }}</td>
        <td style="text-align: center;">
        @php
            $diskon = json_decode($d->diskon, true);
        @endphp
        {{ !empty($diskon) && is_array($diskon) ? implode('+', $diskon) : '0' }}
        </td>
        <td style="text-align: right;">{{ number_format($d->total,0,',','.') }}</td>
      </tr>
      @endforeach

      {{-- Tambah baris kosong supaya footer selalu turun --}}
      @for($k = count($details); $k < 10; $k++)
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
        <td width="25%" valign="top">
          Hormat Kami,<br><br><br><br>
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td width="35%" style="text-align: center;">
          Bila sudah jatuh tempo<br>
          Mohon transfer ke :<br>
          <b>BCA : 506 082 9499 <br>
          BRI : 0587 0100 1434 535</b> <br>
          A/N : GO GIOK LIE <br>
          <b>TERIMA KASIH</b>
        </td>
        <td width="5%" valign="top" style="text-align: left;">
          Subtotal <br>
          Disc {{ $nota->diskon_persen }}% <br>
          <b>Total </b>
        </td>
        <td width="2%" valign="top" style="text-align: left;">
          : <br>
          : <br>
          <b> :</b>
        </td>
        <td width="20%" valign="top" style="text-align: right;">
           Rp. {{ number_format($nota->subtotal,0,',','.') }} <br>
           Rp. {{ number_format($nota->diskon_rupiah,0,',','.') }} <br>
          <b>  Rp. {{ number_format($nota->total_harga,0,',','.') }}</b>
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
