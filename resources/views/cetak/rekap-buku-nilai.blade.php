<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Buku Nilai - {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; }
        .info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
        td.text-left { text-align: left; }
        .kosong { background-color: #ffe4e6; color: #e11d48; font-weight: bold; }
        @media print {
            .no-print { display: none; }
            @page { size: landscape; margin: 1cm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; background: #2563eb; color: #fff; border: none; cursor: pointer; border-radius: 5px;">Cetak Dokumen</button>
        <button onclick="window.close()" style="padding: 8px 15px; background: #4b5563; color: #fff; border: none; cursor: pointer; border-radius: 5px; margin-left: 10px;">Tutup</button>
    </div>

    <div class="header">
        <h2>REKAPITULASI BUKU NILAI KELAS</h2>
        <p>Mata Pelajaran: <strong>{{ $mapel->nama_pelajaran }}</strong> | Kelas: <strong>{{ $kelas->nama_kelas }}</strong></p>
    </div>

    @if($penilaians->isEmpty())
        <p style="text-align: center; font-style: italic; color: #666;">Belum ada kegiatan penilaian (Sumatif/Sikap) untuk kelas dan mata pelajaran ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;">No</th>
                    <th rowspan="2" style="text-align: left;">Nama Lengkap Siswa</th>
                    <th colspan="{{ $penilaians->count() }}">Rincian Penilaian (Kosong = Merah)</th>
                </tr>
                <tr>
                    @foreach($penilaians as $p)
                        <th style="font-size: 10px;">
                            {{ $p->jenis_nilai }}<br>
                            <span style="font-weight: normal;">{{ \Carbon\Carbon::parse($p->tanggal_penilaian)->format('d/m') }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left" style="text-transform: uppercase; font-size:11px;">{{ $data['siswa']->nama_lengkap }}</td>
                        @foreach($penilaians as $p)
                            @php $nilai = $data['nilai'][$p->id]; @endphp
                            <td class="{{ is_null($nilai) ? 'kosong' : '' }}">
                                {{ is_null($nilai) ? '-' : $nilai }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>