<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Validasi Data Siswa</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans text-gray-800 antialiased">

    @php
        if (!isset($pengaturan)) {
            try { $pengaturan = \Illuminate\Support\Facades\Schema::hasTable('pengaturan') ? \App\Models\Pengaturan::first() : null; } catch (\Exception $e) { $pengaturan = null; }
        }
        
        $isAktif = in_array($siswa->status_siswa, ['Aktif', 'Mutasi Masuk']);
        $sekolah = $pengaturan->nama_sekolah ?? 'SMA Negeri 1 Malingping';
    @endphp

    <!-- Container Dokumen (Tengah Layar, Lebar Dokumen Resmi) -->
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
        
        <!-- Header Gaya Pemerintahan/Pendidikan -->
        <div class="bg-[#1E3A8A] px-6 py-5 border-b-4 border-yellow-400 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white uppercase tracking-wide">Hasil Pencarian Data</h1>
                <p class="text-blue-100 text-sm mt-1 font-medium">Sistem Informasi Manajemen {{ $sekolah }}</p>
            </div>
            <!-- Ikon Segel/Verifikasi -->
            <div class="hidden sm:flex shrink-0 w-12 h-12 bg-white/10 rounded-full items-center justify-center">
                <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            
            <!-- Notifikasi Status -->
            <div class="mb-8 p-4 rounded-md flex items-start gap-4 border {{ $isAktif ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                @if($isAktif)
                    <div class="bg-green-600 rounded-full p-1.5 text-white shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-green-800 font-bold text-base">Data Ditemukan dan Valid</h3>
                        <p class="text-green-700 text-sm mt-0.5">Identitas ini tercatat resmi di pangkalan data sebagai siswa aktif.</p>
                    </div>
                @else
                    <div class="bg-red-600 rounded-full p-1.5 text-white shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-red-800 font-bold text-base">Siswa Tidak Aktif</h3>
                        <p class="text-red-700 text-sm mt-0.5">Data ditemukan, namun siswa yang bersangkutan berstatus: <b>{{ $siswa->status_siswa }}</b>.</p>
                    </div>
                @endif
            </div>

            <!-- Tabel Data Identitas (Gaya NISN) -->
            <div class="border-t-2 border-gray-800">
                <table class="w-full text-sm sm:text-base text-left border-collapse">
                    <tbody>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 w-[140px] sm:w-[200px] align-top">Nama Lengkap</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold text-gray-900 uppercase">{{ $siswa->nama_lengkap }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 bg-gray-50/50 hover:bg-gray-50">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 align-top">NISN</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold text-gray-900">{{ $siswa->nisn ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 align-top">NIS</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold text-gray-900">{{ $siswa->nis ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 bg-gray-50/50 hover:bg-gray-50">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 align-top">Tempat, Tanggal Lahir</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold text-gray-900">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y') : '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 align-top">Jenis Kelamin</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold text-gray-900">{{ $siswa->jenis_kelamin }}</td>
                        </tr>
                        <tr class="border-b border-gray-200 bg-gray-50/50 hover:bg-gray-50">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 align-top">Kelas</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold text-gray-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="py-3.5 px-3 font-semibold text-gray-600 align-top">Status Pendaftaran</td>
                            <td class="py-3.5 px-2 w-[10px] align-top text-gray-400">:</td>
                            <td class="py-3.5 px-3 font-bold {{ $isAktif ? 'text-green-700' : 'text-red-700' }}">
                                {{ $siswa->status_siswa ?? 'Tidak Aktif' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Area Tombol Bawah -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-center">
                <a href="{{ url('/') }}" class="bg-[#1E3A8A] hover:bg-blue-900 text-white text-sm font-semibold py-2.5 px-8 rounded flex items-center gap-2 transition-colors focus:ring-4 focus:ring-blue-100 outline-none shadow-sm">
                    Kembali ke Halaman Utama
                </a>
            </div>

        </div>
    </div>

</body>
</html>