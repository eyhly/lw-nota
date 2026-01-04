<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat PDF</title>
  <style>
    @page {
        margin-top: 9rem; 
        margin-bottom: 20px;
        margin-left: 1rem;
        margin-right: 1rem;
    }

    @font-face {
      font-family: 'MartianMono';
      src: url("{{ public_path('fonts/MartianMono-Regular.ttf') }}") format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    body {
      font-family: 'MartianMono', monospace;
      font-size: 13px;
      text-transform: uppercase;
      padding-bottom: 3rem;
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

    .footer {
      position: fixed;
      bottom: 15px;
      width: 100%;
    }

    .faktur {
      font-size: 15px;
      font-weight: bold;
      text-align: center;
    }

    .page-header {
        position: fixed;
        top: -7rem;  /* Posisi di area margin atas */
        left: 0;
        right: 0;
        width: 100%;
    }

    .kendaraan {
    font-size: 10px;
    }

    .coly {
    font-size: 1rem;
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

  <!-- Header Nota -->
<div class="page-header"> 
  <table style="margin-top:0px; margin-bottom: 1rem;" class="header">
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

  <table style="margin-bottom: 0.5rem">
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
</div>

  <!-- Detail Barang -->
  <table>
    <thead>
      <tr>
        <th style="text-align: center;" width="2%">NO</th>
        <th style="text-align: center;" width="11%">COLY</th>
        <th style="text-align: center;" width="11%">ISI</th>
        <th style="padding-left: 5px; text-align: left;" width="48%">NAMA BARANG</th>
        <th style="padding-left: 5px; text-align: left;" width="28%">KETERANGAN</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @foreach($suratjalan->detailsj as $d)
      <tr>
        <td style="text-align: center;">{{ $no++ }}</td>
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
      <tr>
        <td colspan="8">&nbsp;</td>
      </tr>     
    </tbody>
  </table>

  <!-- Footer -->
  <div class="footer">    
    <hr>
    <table style="margin-top:0px; margin-bottom: 0.5rem;">
      <tr>
        <td class="coly" width="10%" valign="top" style="text-align: left;">
          <b>Total: {{$suratjalan->total_coly}} Coly </b>
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
  </div>

</body>
</html>
