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
  </style>
</head>
<body>

@foreach($chunks as $chunkIndex => $details)
  <!-- Header Nota -->
  <table>
    <tr>
      <td width="30%">
        <b>MENTARI JAYA</b><br>
        <b>SURABAYA</b>
      </td>
      <td width="40%" align="center">
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
        {{ $nota->pembeli }} <br>
        {{ $nota->alamat }}
      </td>
    </tr>
  </table>
  <br>

  <!-- Detail Barang -->
  <table>
    <thead>
      <tr>
        <th align="center">NO</th>
        <th align="left">NAMA BARANG</th>
        <th align="center">COLY</th>
        <th align="center">ISI</th>
        <th align="center">TOTAL QTY</th>
        <th align="right">HARGA</th>
        <th align="center">% DISC</th>
        <th align="right">SUBTOTAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach($details as $i => $d)
      <tr>
        <td align="center">{{ ($chunkIndex*10) + $i + 1 }}</td>
        <td align="left">{{ $d->nama_barang }}</td>
        <td align="center">
            <div class="d-flex justify-content-between w-100">
                <span class="text-end w-50">{{ $d->coly }}</span>
                <span class="text-start w-50">{{ $d->satuan_coly }}</span>
            </div>
        </td>
        <td align="center">{{ $d->qty_isi }} {{ $d->nama_isi }}</td>
        <td align="center">{{ $d->jumlah }} {{ $d->nama_isi }}</td>
        <td align="right">{{ number_format($d->harga,0,',','.') }}</td>
        <td align="center">
        @php
            $diskon = json_decode($d->diskon, true);
        @endphp
        {{ !empty($diskon) && is_array($diskon) ? implode('+', $diskon) : '0' }}
        </td>
        <td align="right">{{ number_format($d->total,0,',','.') }}</td>
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
    <hr>
    <table>
      <tr>
        <td width="25%" valign="top">
          Hormat Kami,<br><br><br><br>
          (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        </td>
        <td width="35%" align="center">
          Bila sudah jatuh tempo<br>
          Mohon transfer ke :<br>
          <b>BCA : 506 082 9499 <br>
          BRI : 0587 0100 1434 535</b> <br>
          A/N : GO GIOK LIE <br>
          <b>TERIMA KASIH</b>
        </td>
        <td width="5%" valign="top" align="left">
          Subtotal <br>
          Disc {{ $nota->diskon_persen }}% <br>
          <b>Total </b>
        </td>
        <td width="2%" valign="top" align="left">
          : <br>
          : <br>
          <b> :</b>
        </td>
        <td width="20%" valign="top" align="right">
           Rp. {{ number_format($nota->subtotal,0,',','.') }} <br>
           Rp. {{ number_format($nota->diskon_rupiah,0,',','.') }} <br>
          <b>  Rp. {{ number_format($nota->total_harga,0,',','.') }}</b>
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
