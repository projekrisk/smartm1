<x-filament-panels::page>
    <!-- Alpine.js Data: Menyimpan ID kategori yang sedang aktif/terbuka -->
    <div class="space-y-4" x-data="{ activeCategory: {{ $kategoris->first()?->id ?? 'null' }} }">
        
        @forelse($kategoris as $kategori)
            <div class="bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl overflow-hidden">
                
                <!-- HEADER COLLAPSE (Tombol untuk Buka/Tutup) -->
                <button 
                    @click="activeCategory = activeCategory === {{ $kategori->id }} ? null : {{ $kategori->id }}"
                    class="w-full flex items-center justify-between px-6 py-4 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors focus:outline-none"
                >
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-o-folder" class="w-5 h-5 text-primary-500" />
                        <h3 class="font-bold text-gray-900 dark:text-white text-base uppercase tracking-wider">{{ $kategori->nama_kategori }}</h3>
                        <span class="bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 text-xs px-2.5 py-0.5 rounded-full font-bold">
                            {{ $kategori->jenis_surat_count ?? $kategori->jenisSurat->count() }} Surat
                        </span>
                    </div>
                    
                    <!-- Ikon Panah (Otomatis Berputar) -->
                    <x-filament::icon 
                        icon="heroicon-m-chevron-down" 
                        class="w-5 h-5 text-gray-500 transition-transform duration-300"
                        x-bind:class="activeCategory === {{ $kategori->id }} ? 'rotate-180' : ''"
                    />
                </button>

                <!-- ISI/BODY COLLAPSE (Daftar Surat) -->
                <div x-show="activeCategory === {{ $kategori->id }}" x-collapse x-cloak>
                    <div class="border-t border-gray-200 dark:border-gray-800">
                        <table class="w-full text-left divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-white dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white w-12 text-center">No</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">Jenis Surat</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">Deskripsi Singkat</th>
                                    <th class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white text-center w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($kategori->jenisSurat as $index => $surat)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                            <div class="flex items-center gap-2">
                                                <x-filament::icon icon="{{ $surat->icon ?? 'heroicon-o-document-text' }}" class="w-5 h-5 text-primary-500" />
                                                {{ $surat->nama_surat }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $surat->deskripsi ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <!-- Otomatis menambahkan /admin/ agar link tidak error -->
                                            <x-filament::button tag="a" href="{{ url('/admin/' . ltrim($surat->url_create, '/')) }}" size="sm" icon="heroicon-o-pencil-square" color="primary">
                                                Buat Surat
                                            </x-filament::button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                            <x-filament::icon icon="heroicon-o-document-magnifying-glass" class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                                            Belum ada format surat yang ditambahkan pada kategori ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        @empty
            <div class="p-6 text-sm text-gray-500 italic bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 text-center shadow-sm">
                Belum ada kategori surat yang dibuat. Silakan tambahkan di menu Kategori Surat.
            </div>
        @endforelse

    </div>
</x-filament-panels::page>