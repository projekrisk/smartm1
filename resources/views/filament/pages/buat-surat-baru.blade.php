<x-filament-panels::page>
    <div x-data="{ activeTab: {{ $kategoris->first()?->id ?? 'null' }} }" class="flex flex-col md:flex-row gap-6">
        
        <!-- SIDEBAR KATEGORI -->
        <div class="w-full md:w-1/4 flex flex-col gap-2">
            <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mb-2 ml-1">Kategori Surat</h3>
            
            @forelse($kategoris as $kategori)
                <button @click="activeTab = {{ $kategori->id }}"
                        :class="activeTab === {{ $kategori->id }} ? 'bg-primary-600 text-white shadow-md transform scale-[1.02]' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800'"
                        class="px-4 py-3 rounded-lg text-left font-bold transition-all duration-200 flex justify-between items-center border border-gray-200 dark:border-gray-800">
                    {{ $kategori->nama_kategori }}
                    <span x-show="activeTab === {{ $kategori->id }}" class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-md">
                        {{ $kategori->jenis_surat_count ?? $kategori->jenisSurat->count() }}
                    </span>
                </button>
            @empty
                <div class="p-4 text-sm text-gray-500 italic bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800">
                    Belum ada kategori.
                </div>
            @endforelse
        </div>

        <!-- DAFTAR SURAT (TABEL KANAN) -->
        <div class="w-full md:w-3/4">
            <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mb-2 ml-1">Pilih Format Surat</h3>
            
            @foreach($kategoris as $kategori)
                <div x-show="activeTab === {{ $kategori->id }}" x-cloak>
                    
                    <!-- TABEL FILAMENT STYLE -->
                    <div class="bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl overflow-hidden">
                        <table class="w-full text-left divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-white/5">
                                <tr>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white w-12 text-center">No</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">Jenis Surat</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">Deskripsi Singkat</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white text-center w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($kategori->jenisSurat as $index => $surat)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                            <div class="flex items-center gap-2">
                                                <x-filament::icon icon="{{ $surat->icon ?? 'heroicon-o-document-text' }}" class="w-5 h-5 text-primary-500" />
                                                {{ $surat->nama_surat }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $surat->deskripsi ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <x-filament::button tag="a" href="{{ url($surat->url_create) }}" size="sm" icon="heroicon-o-pencil-square" color="primary">
                                                Buat Surat
                                            </x-filament::button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                            <x-filament::icon icon="heroicon-o-document-magnifying-glass" class="w-8 h-8 text-gray-400 mx-auto mb-2" />
                                            Belum ada format surat yang ditambahkan pada kategori ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</x-filament-panels::page>