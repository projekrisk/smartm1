<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keadaan Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-family: Arial, Helvetica, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .cetak-kertas { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
        .tabel-cetak { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabel-cetak th, .tabel-cetak td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; font-size: 11px; }
        .tabel-cetak th { background-color: #f3f4f6; font-weight: bold; }
    </style>
</head>
<body class="bg-gray-200 text-black text-[11px]">

    <div class="no-print fixed top-5 left-5 z-50 flex gap-2">
        <button onclick="window.close()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 font-bold">&larr; Tutup Tab</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500 font-bold">Cetak Laporan</button>
    </div>

    <div class="flex justify-center py-10 print:py-0 print:block min-w-max">
        <div class="cetak-kertas bg-white shadow-2xl rounded p-[1cm] mx-auto min-w-[29.7cm] flex flex-col">

            <!-- KOP LAPORAN -->
            <div style="display: flex; align-items: center; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 15px;">
                <div style="width: 70px; height: 70px; margin-right: 15px; flex-shrink: 0; display: flex; justify-content: center; align-items: center;">
                    @if(isset($pengaturan) && $pengaturan->logo_sekolah)
                        <img src="{{ url('/uploads/' . $pengaturan->logo_sekolah) }}" alt="Logo Sekolah" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    @else
                        <div style="width: 60px; height: 60px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">Logo</div>
                    @endif
                </div>
                <div style="text-align: left; flex: 1;">
                    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase;">PEMERINTAH PROVINSI BANTEN</div>
                    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase;">DINAS PENDIDIKAN DAN KEBUDAYAAN</div>
                    <div style="font-size: 18px; font-weight: bold; text-transform: uppercase; margin-top: 3px;">{{ isset($pengaturan) && $pengaturan->nama_sekolah ? $pengaturan->nama_sekolah : 'NAMA SEKOLAH' }}</div>
                </div>
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0;">Laporan Bulanan Keadaan Siswa</h2>
                <p style="font-size: 12px; margin: 5px 0 0 0;">Bulan: <strong>{{ $bulanNama }}</strong></p>
            </div>

            @php
                $globalMutasiIdx = 0;
                $flatMasuk = $mutasiMasukPrint->values();
                $flatKeluar = $mutasiKeluarPrint->values();
                $maxMutasi = max(count($flatMasuk), count($flatKeluar));
            @endphp

            <table class="tabel-cetak">
                <thead>
                    <tr>
                        <th rowspan="3" style="width: 80px;">Kelas</th>
                        <th colspan="3">Bulan Lalu</th>
                        <th colspan="6">Bulan Ini</th>
                        <th colspan="3">Bulan Ini</th>
                        <th colspan="4">Siswa Mutasi</th>
                        <th rowspan="3" style="width: 100px;">Keterangan</th>
                    </tr>
                    <tr>
                        <th rowspan="2" style="width: 25px;">L</th>
                        <th rowspan="2" style="width: 25px;">P</th>
                        <th rowspan="2" style="width: 30px;">Jml</th>
                        <th colspan="3">Masuk</th>
                        <th colspan="3">Keluar</th>
                        <th rowspan="2" style="width: 25px;">L</th>
                        <th rowspan="2" style="width: 25px;">P</th>
                        <th rowspan="2" style="width: 30px;">Jml</th>
                        <th colspan="2">Masuk</th>
                        <th colspan="2">Keluar</th>
                    </tr>
                    <tr>
                        <th style="width: 20px;">L</th><th style="width: 20px;">P</th><th style="width: 25px;">Jml</th>
                        <th style="width: 20px;">L</th><th style="width: 20px;">P</th><th style="width: 25px;">Jml</th>
                        <th style="width: 140px;">Nama</th><th style="width: 40px;">Kelas</th>
                        <th style="width: 140px;">Nama</th><th style="width: 40px;">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $tingkat => $kelasData)
                        @foreach($kelasData as $namaKelas => $d)
                            <tr>
                                <td style="font-weight: bold;">{{ $namaKelas }}</td>
                                <td>{{ $d['lalu_L'] ?: '' }}</td>
                                <td>{{ $d['lalu_P'] ?: '' }}</td>
                                <td style="font-weight: bold; background: #fafafa;">{{ ($d['lalu_L'] + $d['lalu_P']) ?: '' }}</td>
                                <td>{{ $d['masuk_L'] ?: '' }}</td><td>{{ $d['masuk_P'] ?: '' }}</td><td style="background: #fafafa;">{{ ($d['masuk_L'] + $d['masuk_P']) ?: '' }}</td>
                                <td>{{ $d['keluar_L'] ?: '' }}</td><td>{{ $d['keluar_P'] ?: '' }}</td><td style="background: #fafafa;">{{ ($d['keluar_L'] + $d['keluar_P']) ?: '' }}</td>
                                <td>{{ $d['sekarang_L'] ?: '' }}</td>
                                <td>{{ $d['sekarang_P'] ?: '' }}</td>
                                <td style="font-weight: bold; background: #f3f4f6;">{{ ($d['sekarang_L'] + $d['sekarang_P']) ?: '' }}</td>

                                @php
                                    $mMasuk = $flatMasuk[$globalMutasiIdx] ?? null;
                                    $mKeluar = $flatKeluar[$globalMutasiIdx] ?? null;
                                    $globalMutasiIdx++;
                                @endphp
                                
                                <td style="text-align: left; padding: 2px 4px; text-transform: uppercase;">{{ $mMasuk ? $mMasuk->nama_lengkap : '' }}</td>
                                <td>{{ $mMasuk ? ($mMasuk->kelas->nama_kelas ?? '-') : '' }}</td>
                                <td style="text-align: left; padding: 2px 4px; text-transform: uppercase;">{{ $mKeluar ? $mKeluar->nama_lengkap : '' }}</td>
                                <td>{{ $mKeluar ? ($mKeluar->kelas->nama_kelas ?? '-') : '' }}</td>
                                <td style="text-align: left; padding: 2px 4px; font-size: 9px;">
                                    @if($mKeluar) {{ $mKeluar->keterangan_status ?? $mKeluar->status_siswa }} @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr style="font-weight: bold; background-color: #fef08a;">
                            <td style="text-align: left; padding-left: 8px;">Jumlah Tingkat {{ $tingkat }}</td>
                            @php $tot = $tingkatTotals[$tingkat]; @endphp
                            <td>{{ $tot['lalu_L'] ?: '' }}</td><td>{{ $tot['lalu_P'] ?: '' }}</td><td>{{ ($tot['lalu_L'] + $tot['lalu_P']) ?: '' }}</td>
                            <td>{{ $tot['masuk_L'] ?: '' }}</td><td>{{ $tot['masuk_P'] ?: '' }}</td><td>{{ ($tot['masuk_L'] + $tot['masuk_P']) ?: '' }}</td>
                            <td>{{ $tot['keluar_L'] ?: '' }}</td><td>{{ $tot['keluar_P'] ?: '' }}</td><td>{{ ($tot['keluar_L'] + $tot['keluar_P']) ?: '' }}</td>
                            <td>{{ $tot['sekarang_L'] ?: '' }}</td><td>{{ $tot['sekarang_P'] ?: '' }}</td><td>{{ ($tot['sekarang_L'] + $tot['sekarang_P']) ?: '' }}</td>
                            <td colspan="5"></td>
                        </tr>
                    @endforeach

                    @while($globalMutasiIdx < $maxMutasi)
                        @php
                            $mMasuk = $flatMasuk[$globalMutasiIdx] ?? null;
                            $mKeluar = $flatKeluar[$globalMutasiIdx] ?? null;
                            $globalMutasiIdx++;
                        @endphp
                        <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td style="text-align: left; padding: 2px 4px; text-transform: uppercase;">{{ $mMasuk ? $mMasuk->nama_lengkap : '' }}</td>
                            <td>{{ $mMasuk ? ($mMasuk->kelas->nama_kelas ?? '-') : '' }}</td>
                            <td style="text-align: left; padding: 2px 4px; text-transform: uppercase;">{{ $mKeluar ? $mKeluar->nama_lengkap : '' }}</td>
                            <td>{{ $mKeluar ? ($mKeluar->kelas->nama_kelas ?? '-') : '' }}</td>
                            <td style="text-align: left; padding: 2px 4px; font-size: 9px;">
                                @if($mKeluar) {{ $mKeluar->keterangan_status ?? $mKeluar->status_siswa }} @endif
                            </td>
                        </tr>
                    @endwhile

                    <tr style="font-weight: bold; background-color: #d1d5db;">
                        <td style="text-align: left; padding-left: 8px;">TOTAL KESELURUHAN</td>
                        @php $g = $grandTotal; @endphp
                        <td>{{ $g['lalu_L'] ?: '' }}</td><td>{{ $g['lalu_P'] ?: '' }}</td><td>{{ ($g['lalu_L'] + $g['lalu_P']) ?: '' }}</td>
                        <td>{{ $g['masuk_L'] ?: '' }}</td><td>{{ $g['masuk_P'] ?: '' }}</td><td>{{ ($g['masuk_L'] + $g['masuk_P']) ?: '' }}</td>
                        <td>{{ $g['keluar_L'] ?: '' }}</td><td>{{ $g['keluar_P'] ?: '' }}</td><td>{{ ($g['keluar_L'] + $g['keluar_P']) ?: '' }}</td>
                        <td>{{ $g['sekarang_L'] ?: '' }}</td><td>{{ $g['sekarang_P'] ?: '' }}</td><td>{{ ($g['sekarang_L'] + $g['sekarang_P']) ?: '' }}</td>
                        <td colspan="5"></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 30px; width: 100%; display: flex; justify-content: space-between; padding: 0 50px; font-size: 11px;">
                <div style="text-align: center;">
                    <br>
                    Kepala Sekolah
                    <br><br><br><br>
                    <strong><span style="text-decoration: underline; text-transform: uppercase;">{{ isset($pengaturan) && $pengaturan->nama_kepala_sekolah ? $pengaturan->nama_kepala_sekolah : '_________________________' }}</span></strong><br>
                    NIP. {{ isset($pengaturan) && $pengaturan->nip_kepala_sekolah ? $pengaturan->nip_kepala_sekolah : '-' }}
                </div>
                <div style="text-align: center;">
                    Malingping, {{ now()->isoFormat('D MMMM Y') }}<br>
                    Kaur Tata Usaha
                    <br><br><br><br>
                    <strong>_________________________</strong><br>
                    NIP. 
                </div>
            </div>

        </div>
    </div>

    <!-- Print Otomatis -->
    <script>
        window.onload = function() {
            setTimeout(() => { window.print(); }, 800);
        }
    </script>
</body>
</html>