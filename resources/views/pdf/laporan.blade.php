<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aktivitas Asatidz</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; margin-bottom: 20px; font-size: 18px; }
        .filter-info { margin-bottom: 15px; font-size: 12px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .text-center { text-align: center; }
        ul { margin: 0; padding-left: 20px; }
        .no-print { display: block; margin-bottom: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #2563eb; color: white; border: none; border-radius: 5px; font-weight: bold;">Cetak / Simpan sebagai PDF</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #64748b; color: white; border: none; border-radius: 5px; font-weight: bold; margin-left: 10px;">Tutup</button>
    </div>
    <h2>Laporan Aktivitas Harian Asatidz</h2>
    <div class="filter-info">
        @if($startDate && $endDate)
            Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong><br>
        @endif
        @if($asatidz)
            Asatidz: <strong>{{ $asatidz->nama }}</strong>
        @else
            Asatidz: <strong>Semua Asatidz</strong>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="25%">Nama Asatidz</th>
                <th width="50%">Rincian Kegiatan</th>
                <th width="10%" class="text-center">Poin</th>
            </tr>
        </thead>
        <tbody>
            @php $hasData = false; @endphp
            @foreach($data as $item)
                @php $hasData = true; @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d/m/Y') }}</td>
                    <td>{{ $item->asatidz->nama }}</td>
                    <td>
                        <ul>
                            @foreach($item->details as $detail)
                                <li>
                                    {{ $detail->masterKegiatan ? $detail->masterKegiatan->nama : 'Unknown' }}
                                    @if($detail->keterangan)
                                        <br><i>({{ $detail->keterangan }})</i>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-center"><strong>{{ $item->total_poin }}</strong></td>
                </tr>
            @endforeach
            @if(!$hasData)
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data laporan pada periode ini.</td>
                </tr>
            @endif
        </tbody>
        @if($hasData)
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold; background-color: #f4f4f4;">Total Poin</td>
                <td class="text-center" style="font-weight: bold; background-color: #f4f4f4;">
                    {{ $total_poin }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
