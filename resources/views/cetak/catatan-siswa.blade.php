<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Catatan Siswa</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header h3, .header p { margin: 0; padding: 2px 0; }
        .header h2 { font-size: 18px; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        th { background-color: #f3f4f6; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .kelas-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; background-color: #e5e7eb; padding: 5px; display: inline-block;}
        
        @media print {
            .no-print { display: none; }
            @page { size: A4 portrait; margin: 1cm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; background: #2563eb; color: #fff; border: none; cursor: pointer; border-radius: 5px;">🖨️ Cetak PDF</button>
        <button onclick="window.close()" style="padding: 8px 15px; background: #4b5563; color: #fff; border: none; cursor: pointer; border-radius: 5px; margin-left: 10px;">Tutup</button>
    </div>

    <div class="header">
        <h2>LAPORAN CATATAN SISWA</h2>
        <h3>Aplikasi Manajemen Akademik Terpadu</h3>
        <p>
            Dicetak Oleh: <strong>{{ $user->name }}</strong> ({{ ucfirst($user->peran) }})<br>
            Tanggal Akses: {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </p>
    </div>

    @if($groupedData->isEmpty())
        <div style="text-align: center; padding: 30px; font-style: italic; border: 1px dashed #000;">
            Tidak ada data catatan yang ditemukan untuk akun Anda.
        </div>
    @else
        @foreach($groupedData as $namaKelas => $siswas)
            <div class="kelas-title">Kelas: {{ $namaKelas }}</div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: 150px;">Nama Siswa</th>
                        <th style="width: 80px;">Tanggal</th>
                        <th>Deskripsi Catatan / Kasus</th>
                        <th>Tindak Lanjut</th>
                        <th style="width: 120px;">Pelapor</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($siswas as $namaSiswa => $catatans)
                        @foreach($catatans as $index => $c)
                            <tr>
                                <!-- Menggabungkan baris (rowspan) jika 1 siswa punya banyak catatan agar tidak berulang -->
                                @if($index === 0)
                                    <td rowspan="{{ count($catatans) }}" class="text-center">{{ $no++ }}</td>
                                    <td rowspan="{{ count($catatans) }}" style="text-transform: uppercase; font-weight: bold;">{{ $namaSiswa }}</td>
                                @endif
                                
                                <td class="text-center">{{ \Carbon\Carbon::parse($c->tanggal)->format('d/m/Y') }}</td>
                                
                                <!-- SESUAIKAN DENGAN NAMA KOLOM DESKRIPSI ANDA (catatan / deskripsi) -->
                                <td>{{ $c->catatan ?? $c->deskripsi ?? '-' }}</td>
                                
                                <td>{{ $c->tindak_lanjut ?? 'Belum ada tindak lanjut' }}</td>
                                
                                <td class="text-center">{{ $c->pencatat->name ?? 'Sistem / Anonim' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif
</body>
</html>