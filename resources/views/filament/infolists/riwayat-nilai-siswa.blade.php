@php
    // Menarik data nilai dari database langsung di View
    $nilais = \App\Models\NilaiRapor::with(['tahunAjaran', 'mataPelajaran'])
        ->where('siswa_id', $getRecord()->siswa_id)
        ->get()
        ->sortBy('tahunAjaran.id')
        ->groupBy(fn($n) => $n->tahunAjaran->nama_tahun . ' (' . $n->tahunAjaran->semester . ')');
        
    $latestSemester = $nilais->keys()->last();
@endphp

<div class="w-full space-y-4">
    @forelse($nilais as $semester => $daftarNilai)
        @php
            $daftarNilai = $daftarNilai->sortBy('mataPelajaran.nama_pelajaran');
            // Menentukan tab semester terakhir terbuka otomatis
            $isOpen = ($semester === $latestSemester) ? 'true' : 'false';
        @endphp
        
        <!-- Setiap tabel memiliki state Alpine.js (open) masing-masing -->
        <div x-data="{ open: {{ $isOpen }} }" class="bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl w-full block">
            
            <!-- Tombol Header Accordion -->
            <button type="button" @click="open = !open" 
                class="w-full text-left px-6 py-4 bg-gray-50 hover:bg-gray-100 dark:bg-white/5 dark:hover:bg-white/10 border-b border-gray-200 dark:border-white/10 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider flex justify-between items-center transition-colors focus:outline-none rounded-t-xl" 
                :class="!open ? 'rounded-b-xl border-b-0' : ''">
                <span>SEMESTER: {{ $semester }}</span>
                <div class="text-gray-500 dark:text-gray-400">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </div>
            </button>

            <!-- Isi Tabel (Bisa Diciutkan) -->
            <div x-show="open" x-collapse x-cloak class="w-full border-t border-gray-200 dark:border-white/10">
                <div class="overflow-x-auto w-full">
                    <table class="w-full min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <thead class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-b border-gray-200 dark:border-white/10">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-12 text-center">No</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Mata Pelajaran</th>
                                <th class="px-6 py-4 font-semibold text-center w-24">Nilai</th>
                                <th class="px-6 py-4 font-semibold text-center w-24">Predikat</th>
                                <th class="px-6 py-4 font-semibold w-2/5">Catatan / Deskripsi Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            @php $no = 1; @endphp
                            @foreach($daftarNilai as $n)
                                @php
                                    $color = $n->nilai_akhir >= 75 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                                @endphp
                                <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150">
                                    <td class="px-6 py-4 text-center font-medium">{{ $no++ }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white whitespace-nowrap">{{ $n->mataPelajaran->nama_pelajaran ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-lg {{ $color }}">{{ $n->nilai_akhir }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-lg text-gray-900 dark:text-white">{{ $n->predikat }}</td>
                                    <td class="px-6 py-4 text-xs leading-relaxed text-gray-600 dark:text-gray-400 break-words whitespace-normal">{{ $n->deskripsi ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center italic text-gray-500 py-6 w-full bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl">
            Belum ada data nilai rapor untuk siswa ini.
        </div>
    @endforelse
</div>