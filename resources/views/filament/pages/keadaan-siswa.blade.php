<x-filament-panels::page>
    <!-- ======================================================= -->
    <!-- BAGIAN 1: TAMPILAN SINGKAT (LAYAR/SCREEN) -->
    <!-- ======================================================= -->
    <div class="space-y-6">
        
        <!-- Filter & Tombol Cetak -->
        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-gray-950/5 dark:ring-white/10">
            <div class="flex flex-col md:flex-row justify-between md:items-end gap-6">
                <div class="flex-1 w-full max-w-2xl">
                    <form wire:submit.prevent="">
                        {{ $this->form }}
                    </form>
                </div>
                <div class="shrink-0 flex items-center mb-1">
                    
                    <!-- MENGGUNAKAN TAG LINK (a) UNTUK MENGARAH KE RUTE CETAK YANG BENAR DAN BERSIH -->
                    <x-filament::button 
                        color="success" 
                        icon="heroicon-o-printer" 
                        tag="a" 
                        target="_blank" 
                        href="{{ url('/cetak/keadaan-siswa?bulan=' . $this->bulan . '&tahun=' . $this->tahun) }}" 
                        size="lg" 
                        class="w-full md:w-auto">
                        Cetak Laporan Bulanan
                    </x-filament::button>

                </div>
            </div>
        </div>

        <div x-data="{ activeTab: 'statistik' }">
            <x-filament::tabs label="Laporan Keadaan Siswa">
                <x-filament::tabs.item alpine-active="activeTab === 'statistik'" x-on:click="activeTab = 'statistik'" icon="heroicon-m-chart-pie">Statistik & Tingkat</x-filament::tabs.item>
                <x-filament::tabs.item alpine-active="activeTab === 'kelas'" x-on:click="activeTab = 'kelas'" icon="heroicon-m-building-office-2">Rincian Per Kelas</x-filament::tabs.item>
                <x-filament::tabs.item alpine-active="activeTab === 'mutasi'" x-on:click="activeTab = 'mutasi'" icon="heroicon-m-arrows-right-left">Mutasi (Keluar/Masuk)</x-filament::tabs.item>
                <x-filament::tabs.item alpine-active="activeTab === 'kelulusan'" x-on:click="activeTab = 'kelulusan'" icon="heroicon-m-academic-cap">Data Kelulusan & Alumni</x-filament::tabs.item>
            </x-filament::tabs>

            <div class="mt-6">
                <!-- TAB 1: STATISTIK -->
                <div x-show="activeTab === 'statistik'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col justify-center">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Siswa Aktif Keseluruhan</h3>
                            <div class="mt-2 text-3xl font-bold text-primary-600 dark:text-primary-400">{{ number_format($totalAll) }}</div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">Tercatat aktif per {{ $bulanNama }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col justify-center">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Laki-laki</h3>
                            <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalL) }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col justify-center">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Perempuan</h3>
                            <div class="mt-2 text-3xl font-bold text-pink-600 dark:text-pink-400">{{ number_format($totalP) }}</div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rekapitulasi Per Tingkat</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Kelompok Tingkat</th>
                                        <th class="px-6 py-3 font-semibold text-center text-gray-950 dark:text-white">Laki-laki</th>
                                        <th class="px-6 py-3 font-semibold text-center text-gray-950 dark:text-white">Perempuan</th>
                                        <th class="px-6 py-3 font-semibold text-center text-gray-950 dark:text-white">Jumlah Keseluruhan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                    @forelse($rekapTingkatScreen as $tingkat => $data)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Tingkat {{ $tingkat }}</td>
                                            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ $data['sekarang_L'] }}</td>
                                            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ $data['sekarang_P'] }}</td>
                                            <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">{{ $data['sekarang_L'] + $data['sekarang_P'] }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400 italic">Belum ada data tingkat.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: KELAS -->
                <div x-show="activeTab === 'kelas'" x-cloak class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rincian Keadaan Siswa Per Kelas</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Per {{ $bulanNama }}</span>
                    </div>
                    <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-6 py-4 font-semibold text-gray-950 dark:text-white">Nama Kelas</th>
                                    <th class="px-6 py-4 font-semibold text-center text-gray-950 dark:text-white">Laki-laki</th>
                                    <th class="px-6 py-4 font-semibold text-center text-gray-950 dark:text-white">Perempuan</th>
                                    <th class="px-6 py-4 font-semibold text-center text-gray-950 dark:text-white">Jumlah Siswa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @forelse($rekapKelasScreen as $kelas => $data)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                        <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $kelas }}</td>
                                        <td class="px-6 py-3 text-center text-gray-500 dark:text-gray-400">{{ $data['L'] }}</td>
                                        <td class="px-6 py-3 text-center text-gray-500 dark:text-gray-400">{{ $data['P'] }}</td>
                                        <td class="px-6 py-3 text-center font-bold text-gray-900 dark:text-white">{{ $data['Total'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400 italic">Belum ada data kelas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: MUTASI MASUK / KELUAR -->
                <div x-show="activeTab === 'mutasi'" x-cloak class="grid grid-cols-1 gap-6">
                    
                    <!-- WIDGET MUTASI GABUNGAN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col justify-center">
                            <h3 class="text-sm font-bold text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">Masuk ({{ $bulanNama }})</h3>
                            <div class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">{{ $mutasiMasukStats['Total'] }}</div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Laki-laki: {{ $mutasiMasukStats['L'] }} | Perempuan: {{ $mutasiMasukStats['P'] }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col justify-center">
                            <h3 class="text-sm font-bold text-red-600 dark:text-red-400 uppercase tracking-wider mb-1">Keluar ({{ $bulanNama }})</h3>
                            <div class="mt-2 text-3xl font-extrabold text-red-600 dark:text-red-400">{{ $mutasiKeluarStats['Total'] }}</div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Laki-laki: {{ $mutasiKeluarStats['L'] }} | Perempuan: {{ $mutasiKeluarStats['P'] }}</div>
                        </div>
                    </div>

                    <!-- TABEL MUTASI MASUK -->
                    <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 bg-green-50 dark:bg-green-900/20">
                            <h3 class="text-lg font-bold text-green-800 dark:text-green-400">Tabel Siswa Mutasi Masuk</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10 shadow-sm">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Nama Siswa</th>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Masuk Ke Kelas</th>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Tanggal Masuk</th>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Keterangan</th>
                                        <th class="px-6 py-3 font-semibold text-right text-gray-950 dark:text-white">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                    @forelse($mutasiMasukScreen as $mutasi)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white uppercase">{{ $mutasi->nama_lengkap }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $mutasi->kelas->nama_kelas ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $mutasi->tanggal_masuk ? \Carbon\Carbon::parse($mutasi->tanggal_masuk)->isoFormat('D MMMM Y') : \Carbon\Carbon::parse($mutasi->created_at)->isoFormat('D MMMM Y') }}</td>
                                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $mutasi->keterangan_status ?? 'Siswa Baru' }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ \App\Filament\Resources\SiswaResource::getUrl('view', ['record' => $mutasi->id]) }}" target="_blank" class="text-primary-600 hover:text-primary-500 font-medium text-sm">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Tidak ada siswa Mutasi Masuk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($mutasiMasukScreen->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900">
                                {{ $mutasiMasukScreen->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- TABEL SISWA KELUAR -->
                    <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 bg-red-50 dark:bg-red-900/20">
                            <h3 class="text-lg font-bold text-red-800 dark:text-red-400">Tabel Siswa Keluar / Berhenti</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10 shadow-sm">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Nama Siswa</th>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Dari Kelas</th>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Tanggal Keluar</th>
                                        <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Status</th>
                                        <th class="px-6 py-3 font-semibold text-right text-gray-950 dark:text-white">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                    @forelse($mutasiKeluarScreen as $mutasi)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white uppercase">{{ $mutasi->nama_lengkap }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $mutasi->kelas->nama_kelas ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $mutasi->tanggal_status ? \Carbon\Carbon::parse($mutasi->tanggal_status)->isoFormat('D MMMM Y') : '-' }}</td>
                                            <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400 uppercase">
                                                {{ $mutasi->status_siswa }}
                                                <div class="text-xs font-normal text-gray-500 mt-1">{{ $mutasi->keterangan_status ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ \App\Filament\Resources\SiswaResource::getUrl('view', ['record' => $mutasi->id]) }}" target="_blank" class="text-primary-600 hover:text-primary-500 font-medium text-sm">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Tidak ada siswa keluar atau berhenti di bulan ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($mutasiKeluarScreen->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900">
                                {{ $mutasiKeluarScreen->links() }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- TAB 4: DATA KELULUSAN & ALUMNI -->
                <div x-show="activeTab === 'kelulusan'" x-cloak class="space-y-6">
                    
                    <!-- WIDGET STATISTIK KELULUSAN GLOBAL -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 text-center flex flex-col justify-center">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Lulusan Laki-laki</h3>
                            <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalAlumniL) }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 border border-gray-200 dark:border-white/10 text-center flex flex-col justify-center">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Lulusan Perempuan</h3>
                            <div class="mt-2 text-3xl font-bold text-pink-600 dark:text-pink-400">{{ number_format($totalAlumniP) }}</div>
                        </div>
                        <div class="bg-gradient-to-br from-primary-500 to-primary-700 shadow-sm rounded-xl p-6 border border-transparent text-center flex flex-col justify-center text-white">
                            <h3 class="text-sm font-bold uppercase tracking-wider opacity-90">Grand Total Alumni</h3>
                            <div class="mt-2 text-4xl font-extrabold">{{ number_format($totalAlumniAll) }}</div>
                        </div>
                    </div>
                    
                    <!-- KOTAK PENCARIAN & TABEL -->
                    <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                        
                        <!-- Header & Kotak Pencarian -->
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 flex flex-col md:flex-row justify-between md:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Data Kelulusan Siswa</h3>
                                <p class="text-xs text-gray-500 mt-1">Gunakan kolom pencarian untuk melihat daftar nama alumni.</p>
                            </div>
                            
                            <!-- Search Input -->
                            <div class="w-full md:w-80 relative flex items-center">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" wire:model.live.debounce.500ms="searchLulusan" class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-white/20 dark:text-white dark:placeholder-gray-400 shadow-sm transition" placeholder="Cari nama atau NISN alumni...">
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            @if(empty($searchLulusan))
                                <!-- TAMPILAN DEFAULT: REKAPITULASI PER TAHUN -->
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-white/10 shadow-sm">
                                        <tr>
                                            <th class="px-6 py-4 font-semibold text-gray-950 dark:text-white">Tahun Kelulusan</th>
                                            <th class="px-6 py-4 font-semibold text-center text-blue-600 dark:text-blue-400">Laki-laki</th>
                                            <th class="px-6 py-4 font-semibold text-center text-pink-600 dark:text-pink-400">Perempuan</th>
                                            <th class="px-6 py-4 font-semibold text-center text-gray-950 dark:text-white">Jumlah Lulusan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        @forelse($alumniPerTahun as $stat)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-base">Lulusan {{ $stat->tahun }}</td>
                                                <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300 font-medium">{{ number_format($stat->jml_L) }} Orang</td>
                                                <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300 font-medium">{{ number_format($stat->jml_P) }} Orang</td>
                                                <td class="px-6 py-4 text-center font-bold text-primary-600 dark:text-primary-400 text-base">{{ number_format($stat->total) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Belum ada riwayat kelulusan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            @else
                                <!-- TAMPILAN PENCARIAN: DAFTAR NAMA ALUMNI -->
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-white/10">
                                        <tr>
                                            <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Nama Siswa & NISN</th>
                                            <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Tahun Lulus</th>
                                            <th class="px-6 py-3 font-semibold text-gray-950 dark:text-white">Kelas Terakhir</th>
                                            <th class="px-6 py-3 font-semibold text-center text-gray-950 dark:text-white">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        @forelse($lulusanScreen as $alumni)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                                <td class="px-6 py-4">
                                                    <div class="font-bold text-gray-900 dark:text-white uppercase">{{ $alumni->nama_lengkap }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">{{ $alumni->nisn ?? $alumni->nis }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                                        {{ $alumni->tanggal_status ? \Carbon\Carbon::parse($alumni->tanggal_status)->format('Y') : '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $alumni->kelas->nama_kelas ?? '-' }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <a href="{{ \App\Filament\Resources\SiswaResource::getUrl('view', ['record' => $alumni->id]) }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-500 transition shadow-sm">Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Tidak ada data alumni yang cocok dengan pencarian Anda.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        
                        <!-- PAGINATION ALUMNI (HANYA MUNCUL SAAT MENCARI) -->
                        @if(!empty($searchLulusan) && $lulusanScreen->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900">
                                {{ $lulusanScreen->links() }}
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-filament-panels::page>