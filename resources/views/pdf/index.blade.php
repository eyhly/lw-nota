<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Nota PDF</title>

  <style>
    @page {
        margin-top: 9rem; 
        margin-bottom: 0.7rem;
        margin-left: 1rem;
        margin-right: 1rem;
    }

    @font-face {
      font-family: 'MartianMono';
      src: url("{{ public_path('fonts/MartianMono-Regular.ttf') }}") format('truetype');
    }

    body {
      font-family: 'MartianMono', monospace;
      font-size: 13px;
      text-transform: uppercase;
      padding-bottom: 6rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      page-break-inside: auto;
    }

    thead {
      display: table-header-group;
    }

    thead tr {
      border-top: 1px solid black;
      border-bottom: 1px solid black;
      page-break-inside: avoid;
    }

    th, td {
      padding: 3px;
      vertical-align: top;
      word-wrap: break-word;
    }

    .faktur {
      font-size: 18px;
      font-weight: bold;
      text-align: center;
    }

    .footer {
      position: fixed;
      bottom: 15px;
      width: 100%;
    }

    .page-header {
        position: fixed;
        top: -7rem;  /* Posisi di area margin atas */
        left: 0;
        right: 0;
        width: 100%;
    }
  </style>
</head>

<body>

<!-- HEADER -->
 <div class="page-header">
<table style="margin-bottom: 0.8rem">
  <tr>
    <td width="30%">
      <b>MENTARI JAYA</b><br>
      <b>SURABAYA</b>
    </td>
    <td width="40%" class="faktur">
      <u>FAKTUR</u>
    </td>
    <td width="30%"></td>
  </tr>
</table>

<table style="margin-bottom: 0.8rem">
  <tr>
    <td width="15%">
      No Nota <br>
      Tanggal <br>
      Jatuh Tempo
    </td>
    <td width="50%">
      : {{ $nota->no_nota }}<br>
      : {{ \Carbon\Carbon::parse($nota->tanggal)->format('d/m/Y') }}<br>
      : {{ \Carbon\Carbon::parse($nota->jt_tempo)->format('d/m/Y') }}
    </td>
    <td width="35%">
      Kepada Yth,<br>
      {{ $nota->nama_toko }}<br>
      @if($nota->pembeli)
        {{ $nota->pembeli }}<br>
      @endif
      {{ $nota->alamat }}
    </td>
  </tr>
</table>
</div>

  
<!-- DETAIL BARANG -->

<table>
  <thead>
    <tr>
      <th style="text-align: left;" width="3%">NO</th>
      <th width="33%">NAMA BARANG</th>
      <th style="text-align: center;" width="10%">COLY</th>
      <th style="text-align: center;" width="10%">ISI</th>
      <th style="text-align: center;" width="11%">TOTAL QTY</th>
      <th style="text-align: right;" width="12%">HARGA</th>
      <th style="text-align: right;" width="8%">DISC</th>
      <th style="text-align: right;" width="13%">SUBTOTAL</th>
    </tr>
  </thead>

  <tbody>
    @php $no = 1; @endphp
    @foreach($nota->details as $d)
    <tr>
      <td style="text-align: left;">{{ $no++ }}</td>
      <td>{{ $d->nama_barang }}</td>
      <td style="text-align: center;">{{ $d->coly }} {{ $d->satuan_coly }}</td>
      <td style="text-align: center;">{{ $d->qty_isi }} {{ $d->nama_isi }}</td>
      <td style="text-align: center;">{{ $d->jumlah }} {{ $d->nama_isi }}</td>
      <td style="text-align: right;">{{ number_format($d->harga,0,',','.') }}</td>
      <td style="text-align: right;">
         {{!empty($d->diskon) ? implode('+', $d->diskon): 0}}
      </td>
      <td style="text-align: right;">{{ number_format($d->total,0,',','.') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<!-- FOOTER -->
<div class="footer">
  <hr>
  <table>
    <tr>
      <td width="30%" style="text-align: center;">
        Hormat Kami,<br><br><br>
        ( __________ )
      </td>
      <td width="40%" style="text-align: center;">
        Bila sudah jatuh tempo<br>
        Mohon transfer ke:<br>
        <b>
          BCA : 506 082 9499<br>
          BRI : 0587 0100 1434 535
        </b><br>
        A/N : GO GIOK LIE<br>
        <b>TERIMA KASIH</b>
      </td>
      <td width="10%" style="text-align: right; line-height: 1.5;">
        Subtotal<br>
        Disc {{ $nota->diskon_persen }}%<br>
        <b>Total</b>
      </td>
      <td width="20%" style="text-align: left; padding-left: 1rem; line-height: 1.5; text-transform: none;">
        Rp {{ number_format($nota->subtotal,0,',','.') }}<br>
        Rp {{ number_format($nota->diskon_rupiah,0,',','.') }}<br>
        <b>Rp {{ number_format($nota->total_harga,0,',','.') }}</b>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
