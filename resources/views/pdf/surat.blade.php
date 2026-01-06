<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat PDF</title>
    <style>
        @page {
            margin-top: 9.5rem;
            margin-bottom: 1.5rem;
            margin-left: 1rem;
            margin-right: 1rem;
        }

        /* Regular */
        @font-face {
            font-family: 'MartianMono';
            src: url("{{ public_path('fonts/NK57 Monospace Sc Sb.otf') }}") format('truetype');
            /* font-weight: 400; */
            font-style: normal;
        }

        /* Bold */
        @font-face {
            font-family: 'MartianMono';
            src: url("{{ public_path('fonts/NK57 Monospace Sc Eb.otf') }}") format('truetype');
            font-weight: 700;
            font-style: normal;
        }

        body {
            font-family: 'MartianMono', monospace;
            font-size: 12.5px;
            font-weight: 400;
            text-transform: uppercase;
            padding-bottom: 7.5rem;
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

        th,
        td {
            /* padding: 3px; */
            vertical-align: top;
            word-wrap: break-word;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            width: 100%;
            height: 8rem;
        }

        .faktur {
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }

        .page-header {
            position: fixed;
            top: -8rem;
            /* Posisi di area margin atas */
            left: 0;
            right: 0;
            width: 100%;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="page-header">
        <table>
            <tr>
                <td width="30%" style="font-size: 14px; font-weight: 700; padding-left: 1rem;">
                    <b>MENTARI JAYA</b><br>
                    <b>SURABAYA</b>
                </td>
                <td width="40%" class="faktur">
                    <u>Surat Jalan</u>
                </td>
                <td width="30%"></td>
            </tr>
        </table>

        <table>
            <tr>
              <td width="15%" style="padding-left: 1rem">
                  <br>
                    No Surat <br>
                    Tanggal
                </td>
                <td width="50%">
                  <br>
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
            @foreach ($suratjalan->detailsj as $d)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td style="text-align: center;">{{ $d->coly }} {{ $d->satuan_coly }}</td>
                    <td style="text-align: center;">{{ $d->qty_isi }} {{ $d->nama_isi }}</td>
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
        <table>
            <tr>
                <td class="coly" width="10%" valign="top" style="text-align: left;">
                    <b>Total: {{ $suratjalan->total_coly }} Coly </b>
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
