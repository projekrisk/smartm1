<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th rowspan="2" style="font-weight: bold; text-align: center; background-color: #f8fafc;">No</th>
                <th rowspan="2" style="font-weight: bold; text-align: center; background-color: #f8fafc;">NISN</th>
                <th rowspan="2" style="font-weight: bold; text-align: center; background-color: #f8fafc; width: 250px;">Nama Lengkap Siswa</th>
                
                @foreach($grupMapel as $namaMapel => $penilaians)
                    <th colspan="{{ count($penilaians) }}" style="font-weight: bold; text-align: center; background-color: #cbd5e1;">
                        {{ $namaMapel }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach($grupMapel as $namaMapel => $penilaians)
                    @foreach($penilaians as $p)
                        <th style="font-weight: bold; text-align: center; background-color: #e2e8f0;">
                            {{ $p->jenis_nilai }}
                        </th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $index => $data)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $data['siswa']->nisn ?? '-' }}</td>
                    <td>{{ $data['siswa']->nama_lengkap }}</td>
                    
                    @foreach($grupMapel as $namaMapel => $penilaians)
                        @foreach($penilaians as $p)
                            @php $nilai = $data['nilai'][$p->id]; @endphp
                            
                            @if(is_null($nilai))
                                <!-- Kotak Merah Jika Kosong -->
                                <td style="text-align: center; background-color: #ffcccc; color: #ff0000; font-weight: bold;">X</td>
                            @else
                                <td style="text-align: center;">{{ $nilai }}</td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>