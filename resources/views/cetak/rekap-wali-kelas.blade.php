<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantauan Wali Kelas - {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; font-size: 16px; text-transform: uppercase; }
        
        /* Pengaturan Scroll untuk Layar Monitor */
        .table-container { width: 100%; overflow-x: auto; margin-bottom: 20px; }
        
        table { border-collapse: collapse; min-width: 100%; white-space: nowrap; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: center; }
        th { background-color: #e5e7eb; font-weight: bold; }
        
        .th-mapel { background-color: #cbd5e1; font-size: 12px; }
        .td-nama { text-align: left; text-transform: uppercase; font-weight: bold; position: sticky; left: 0; background-color: #fff; z-index: 10; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .td-nisn { position: sticky; left: 0; background-color: #fff; z-index: 10; }
        
        /* Indikator Peringatan (Kosong = Merah) */
        .kosong { background-color: #ffe4e6 !important; color: #e11d48; font-weight: bold; }
        
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 10px; font-size: 9px; }
            @page { size: legal landscape; margin: 0.5cm; } /* Memaksa kertas F4/Legal Mendatar agar muat banyak */
            .table-container { overflow-x: visible; }
            .td-nama, .td-nisn { position: static; box-shadow: none; }
            table { white-space: normal; }
            th, td { word-wrap: break-word; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 15px; background: #2563eb; color: #fff; border: none; cursor: pointer; border-radius: 5px; font-weight:bold;">🖨️ Cetak Matriks</button>
        <button onclick="window.close()" style="padding: 10px 15px; background: #4b5563; color: #fff; border: none; cursor: pointer; border-radius: 5px; margin-left: 10px;">Tutup</button>
        <span style="margin-left: 15px; color: #e11d48; font-weight: bold;">(Tips: Geser tabel ke kanan jika terpotong di layar)</span>
    </div>

    <div class="header">
        <h2>REKAPITULASI PANTAUAN NILAI - WALI KELAS</h2>
        <p>Kelas Binaan: <strong>{{ $kelas->nama_kelas }}</strong> | Tanggal Akses: <strong>{{ \Carbon\Carbon::now()->format('d M Y') }}</strong></p>
    </div>

    @if(empty($grupMapel))
        <div style="text-align: center; padding: 40px; border: 1px dashed #ccc; background: #f9fafb;">
            <h3>Belum Ada Data Penilaian</h3>
            <p>Guru mata pelajaran di kelas ini belum membuat catatan nilai Sumatif/Sikap sama sekali.</p>
        </div>
    @else
        <div class="table-container">
            <table>
                <thead>
                    <!-- BARIS 1: Header Nama Mapel -->
                    <tr>
                        <th rowspan="2" style="width: 30px;">No</th>
                        <th rowspan="2" class="td-nisn" style="width: 80px;">NISN</th>
                        <th rowspan="2" class="td-nama" style="min-width: 200px;">Nama Lengkap Siswa</th>
                        
                        @foreach($grupMapel as $namaMapel => $penilaians)
                            <th colspan="{{ count($penilaians) }}" class="th-mapel">{{ $namaMapel }}</th>
                        @endforeach
                    </tr>
                    
                    <!-- BARIS 2: Header Jenis Penilaian (Sumatif 1, Sikap, dll) -->
                    <tr>
                        @foreach($grupMapel as $namaMapel => $penilaians)
                            @foreach($penilaians as $p)
                                <th style="font-size: 9px; padding: 4px;">
                                    {{ $p->jenis_nilai }}
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($rekap as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="td-nisn">{{ $data['siswa']->nisn ?? '-' }}</td>
                            <td class="td-nama">{{ $data['siswa']->nama_lengkap }}</td>
                            
                            <!-- Isi Nilai per Mapel & per Penilaian -->
                            @foreach($grupMapel as $namaMapel => $penilaians)
                                @foreach($penilaians as $p)
                                    @php $nilai = $data['nilai'][$p->id]; @endphp
                                    <td class="{{ is_null($nilai) ? 'kosong' : '' }}">
                                        {{ is_null($nilai) ? 'X' : $nilai }}
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>