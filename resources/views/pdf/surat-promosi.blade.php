<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat {{ $jenisLabel }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            padding: 35px 40px;
            color: #333;
        }
        .kop {
            text-align: center;
            border-bottom: 3px double #1a56db;
            padding-bottom: 14px;
            margin-bottom: 28px;
        }
        .kop .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a56db;
            letter-spacing: 1.5px;
        }
        .kop .company-addr {
            font-size: 10px;
            color: #666;
            margin-top: 4px;
        }
        .no-surat {
            text-align: right;
            font-size: 11px;
            margin-bottom: 6px;
        }
        .perihal {
            text-align: center;
            margin-bottom: 24px;
        }
        .perihal h2 {
            font-size: 15px;
            margin: 0 0 4px 0;
            text-decoration: underline;
        }
        .perihal .jenis {
            font-size: 13px;
            font-weight: bold;
            color: #1a56db;
        }
        .pembuka {
            margin-bottom: 18px;
            text-align: justify;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table td {
            padding: 4px 10px;
            vertical-align: top;
        }
        .data-table .label {
            width: 140px;
            font-weight: bold;
            color: #555;
        }
        .data-table .value {
            width: 20px;
            text-align: center;
        }
        .data-table .sep {
            width: 16px;
            text-align: center;
        }
        .data-table .divider td {
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }
        .data-table .baru td {
            font-weight: bold;
            color: #1a56db;
        }
        .alasan {
            margin-bottom: 24px;
            text-align: justify;
        }
        .alasan .label {
            font-weight: bold;
        }
        .penutup {
            margin-top: 30px;
            text-align: justify;
        }
        .ttd {
            float: right;
            text-align: center;
            margin-top: 10px;
            width: 260px;
        }
        .ttd .kota {
            margin-bottom: 60px;
        }
        .ttd .nama {
            font-weight: bold;
            margin-top: 4px;
        }
        .ttd .jabatan-ttd {
            font-size: 11px;
            color: #666;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="company-name">PT. JOHEN SUKSES ABADI</div>
        <div class="company-addr">
            Summarecon Gedebage, Ruko Plaza Topaz Commercial No.60, Summarecon<br>
            Gedebage, Kota Bandung, Jawa Barat, 40294
        </div>
    </div>

    @if($promotion->nomor_surat)
    <div class="no-surat">
        Nomor: {{ $promotion->nomor_surat }}
    </div>
    @endif

    <div class="perihal">
        <h2>SURAT KEPUTUSAN</h2>
        <div class="jenis">{{ $jenisLabel }}</div>
    </div>

    <div class="pembuka">
        Yang bertanda tangan di bawah ini, Pimpinan PT. Johen Sukses Abadi, dengan ini memutuskan untuk melakukan {{ $promotion->jenis === 'promosi' ? 'kenaikan jabatan' : ($promotion->jenis === 'demosi' ? 'penurunan jabatan' : 'mutasi jabatan') }} kepada:
    </div>

    <table class="data-table">
        <tr>
            <td class="label">Nama</td>
            <td class="sep">:</td>
            <td>{{ $employee->nama }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="sep">:</td>
            <td>{{ $employee->nik }}</td>
        </tr>
        <tr>
            <td class="label">Divisi</td>
            <td class="sep">:</td>
            <td>{{ $promotion->divisi_lama ?? '-' }}</td>
        </tr>
        <tr class="divider">
            <td class="label">Jabatan Lama</td>
            <td class="sep">:</td>
            <td>{{ $promotion->posisi_lama }}</td>
        </tr>
        <tr class="baru">
            <td class="label">Jabatan Baru</td>
            <td class="sep">:</td>
            <td>{{ $promotion->posisi_baru }}</td>
        </tr>
        <tr class="baru">
            <td class="label">Divisi Baru</td>
            <td class="sep">:</td>
            <td>{{ $promotion->divisi_baru ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Atasan Langsung</td>
            <td class="sep">:</td>
            <td>{{ $promotion->atasan_baru ?? $promotion->atasan_lama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Efektif</td>
            <td class="sep">:</td>
            <td>{{ $promotion->tanggal_efektif->isoFormat('D MMMM Y') }}</td>
        </tr>
    </table>

    @if($promotion->alasan)
    <div class="alasan">
        <span class="label">Alasan {{ $promotion->jenis === 'promosi' ? 'Promosi' : ($promotion->jenis === 'demosi' ? 'Demosi' : 'Mutasi') }}:</span><br>
        {{ $promotion->alasan }}
    </div>
    @endif

    <div class="penutup">
        Demikian surat keputusan ini dibuat untuk diketahui dan dilaksanakan dengan penuh tanggung jawab.
    </div>

    <div class="ttd">
        <div class="kota">Bandung, {{ $promotion->tanggal_efektif->isoFormat('D MMMM Y') }}</div>
        <div>PT. Johen Sukses Abadi,</div>
        <div style="margin-top: 60px;"></div>
        <div class="nama">( ____________________ )</div>
        <div class="jabatan-ttd">Pimpinan</div>
    </div>
    <div class="clearfix"></div>
</body>
</html>
