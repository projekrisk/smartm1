<x-filament-panels::page>
    <div class="space-y-6">
        
        <!-- Filter Card -->
        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-6 ring-1 ring-gray-950/5 dark:ring-white/10">
            <form wire:submit.prevent="">
                {{ $this->form }}
            </form>
        </div>

        <!-- Statistics Widget -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 text-center flex flex-col justify-center">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Target Pengumpulan</h3>
                <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }} <span class="text-sm font-normal text-gray-500">Kelas/Mapel</span></div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 text-center flex flex-col justify-center">
                <h3 class="text-sm font-medium text-green-700 dark:text-green-400">Tuntas 100%</h3>
                <div class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['sudah'] }}</div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 text-center flex flex-col justify-center">
                <h3 class="text-sm font-medium text-red-700 dark:text-red-400">Belum Tuntas (Proses)</h3>
                <div class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['belum'] }}</div>
            </div>
        </div>

        <!-- Tabs untuk Tingkat -->
        @if(count($groupedData) > 0)
            <div x-data="{ activeTab: '{{ array_key_first($groupedData) }}' }" class="space-y-6">
                <!-- Tampilan Tab Header -->
                <x-filament::tabs label="Tingkat Kelas">
                    @foreach($groupedData as $tingkat => $kelasGroups)
                        <x-filament::tabs.item 
                            alpine-active="activeTab === '{{ $tingkat }}'" 
                            x-on:click="activeTab = '{{ $tingkat }}'" 
                            icon="heroicon-m-building-office-2">
                            {{ $tingkat }}
                        </x-filament::tabs.item>
                    @endforeach
                </x-filament::tabs>

                <!-- Isi dari masing-masing Tab -->
                <div class="mt-6">
                    @foreach($groupedData as $tingkat => $kelasGroups)
                        <div x-show="activeTab === '{{ $tingkat }}'" x-cloak>
                            <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                                
                                <div class="divide-y divide-gray-200 dark:divide-white/10">
                                    @foreach($kelasGroups as $namaKelas => $jadwalList)
                                        @php
                                            $totalMapelDiKelas = count($jadwalList);
                                            $selesaiMapelDiKelas = collect($jadwalList)->where('status_pengumpulan', 'Selesai')->count();
                                            $isSemuaSelesai = $totalMapelDiKelas > 0 && $selesaiMapelDiKelas === $totalMapelDiKelas;
                                        @endphp
                                        
                                        <!-- Komponen Alpine.js untuk fitur buka-tutup (Accordion) Kelas -->
                                        <div x-data="{ open: false }" class="w-full">
                                            <button type="button" @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-white/5 transition-colors focus:outline-none">
                                                <div class="flex items-center gap-4">
                                                    <h4 class="text-md font-bold text-gray-900 dark:text-white">Kelas {{ $namaKelas }}</h4>
                                                    <!-- Indikator Badge Kelas -->
                                                    @if($isSemuaSelesai)
                                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded dark:bg-green-900/30 dark:text-green-400">
                                                            Tuntas ({{ $selesaiMapelDiKelas }}/{{ $totalMapelDiKelas }} Mapel)
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded dark:bg-yellow-900/30 dark:text-yellow-400">
                                                            Proses ({{ $selesaiMapelDiKelas }}/{{ $totalMapelDiKelas }} Mapel)
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-gray-500 dark:text-gray-400">
                                                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                                </div>
                                            </button>
                                            
                                            <!-- Isi Tabel (Tampil jika open = true) -->
                                            <div x-show="open" x-collapse x-cloak class="border-t border-gray-200 dark:border-white/10 dark:bg-gray-800/50">
                                                <div class="overflow-x-auto w-full p-4">
                                                    <table class="w-full text-sm text-left">
                                                        <thead class="border-b border-gray-200 dark:border-white/10">
                                                            <tr>
                                                                <th class="px-4 py-2 font-semibold text-gray-900 dark:text-gray-200 w-12 text-center">No</th>
                                                                <th class="px-4 py-2 font-semibold text-gray-900 dark:text-gray-200">Mata Pelajaran</th>
                                                                <th class="px-4 py-2 font-semibold text-gray-900 dark:text-gray-200">Guru Pengampu</th>
                                                                <th class="px-4 py-2 font-semibold text-center text-gray-900 dark:text-gray-200 w-48">Progres Siswa</th>
                                                                <th class="px-4 py-2 font-semibold text-center text-gray-900 dark:text-gray-200">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                                            @php $no = 1; @endphp
                                                            @foreach($jadwalList as $jadwal)
                                                                <tr class="dark:hover:bg-gray-700 transition-colors duration-150">
                                                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $no++ }}</td>
                                                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $jadwal->mataPelajaran->nama_pelajaran ?? '-' }}</td>
                                                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $jadwal->guru->name ?? '-' }}</td>
                                                                    <td class="px-4 py-3 text-center">
                                                                        <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                                            <strong class="text-gray-900 dark:text-white">{{ $jadwal->siswa_dinilai }}</strong> dari {{ $jadwal->total_siswa }} dinilai
                                                                        </div>
                                                                        <!-- Visual Progress Bar -->
                                                                        @php
                                                                            $percent = $jadwal->total_siswa > 0 ? min(100, round(($jadwal->siswa_dinilai / $jadwal->total_siswa) * 100)) : 0;
                                                                            // Pewarnaan dinamis: Hijau, Kuning, Merah
                                                                            $barColor = 'bg-red-500';
                                                                            if ($percent == 100) {
                                                                                $barColor = 'bg-green-500';
                                                                            } elseif ($percent > 0) {
                                                                                $barColor = 'bg-yellow-400';
                                                                            }
                                                                        @endphp
                                                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 mt-1.5 overflow-hidden flex">
                                                                            <div class="{{ $barColor }} h-1.5 transition-all duration-500" style="width: {{ $percent }}%"></div>
                                                                            <!-- Sisa bar kosong -->
                                                                            @if($percent < 100)
                                                                            <div class="bg-gray-300 dark:bg-gray-500 h-1.5 transition-all duration-500" style="width: {{ 100 - $percent }}%"></div>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-4 py-3 text-center">
                                                                        @if($jadwal->status_pengumpulan === 'Selesai')
                                                                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                                Selesai
                                                                            </span>
                                                                        @elseif($jadwal->status_pengumpulan === 'Proses')
                                                                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                                Sebagian
                                                                            </span>
                                                                        @else
                                                                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                                Belum Mulai
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl p-10 ring-1 ring-gray-950/5 dark:ring-white/10 text-center">
                <p class="text-gray-500 dark:text-gray-400 italic">Belum ada data jadwal pelajaran untuk tahun ajaran ini.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>