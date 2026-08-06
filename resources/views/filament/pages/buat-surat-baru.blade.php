<x-filament-panels::page>
    <div x-data="{ activeTab: {{ $kategoris->first()?->id ?? 'null' }} }" class="flex flex-col md:flex-row gap-6">
        
        <!-- SIDEBAR KATEGORI -->
        <div class="w-full md:w-1/4 flex flex-col gap-2">
            <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mb-2 ml-1">Kategori Surat</h3>
            
            @forelse($kategoris as $kategori)
                <button @click="activeTab = {{ $kategori->id }}"
                        :class="activeTab === {{ $kategori->id }} ? 'bg-primary-600 text-white shadow-md transform scale-[1.02]' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-5 py-4 rounded-xl text-left font-bold transition-all duration-200 flex justify-between items-center border border-gray-100 dark:border-gray-800">
                    {{ $kategori->nama_kategori }}
                    <span x-show="activeTab === {{ $kategori->id }}" class="bg-white/20 text-white text-xs px-2 py-1 rounded-lg">{{ $kategori->jenis_surat_count ?? $kategori->jenisSurat->count() }}</span>
                </button>
            @empty
                <div class="p-4 text-sm text-gray-500 italic bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    Belum ada kategori. Silakan buat di menu Kategori Surat.
                </div>
            @endforelse
        </div>

        <!-- DAFTAR SURAT (GRID KANAN) -->
        <div class="w-full md:w-3/4">
            <h3 class="font-bold text-gray-500 uppercase text-xs tracking-wider mb-2 ml-1">Pilih Format Surat</h3>
            
            @foreach($kategoris as $kategori)
                <div x-show="activeTab === {{ $kategori->id }}" x-cloak 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    @forelse($kategori->jenisSurat as $surat)
                        <a href="{{ url($surat->url_create) }}" class="block bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm hover:shadow-lg hover:ring-2 hover:ring-primary-500 border border-gray-100 dark:border-gray-700 transition-all duration-200 group">
                            <div class="flex items-center gap-4">
                                <div class="p-4 bg-primary-50 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 rounded-xl group-hover:scale-110 group-hover:bg-primary-100 transition-all duration-200">
                                    <x-filament::icon icon="{{ $surat->icon ?? 'heroicon-o-document-text' }}" class="w-7 h-7" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ $surat->nama_surat }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $surat->deskripsi ?? 'Klik untuk membuat surat ini.' }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-2 flex flex-col items-center justify-center p-12 text-gray-500 italic border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                            <x-filament::icon icon="heroicon-o-document-magnifying-glass" class="w-12 h-12 text-gray-400 mb-3" />
                            Belum ada format surat untuk kategori {{ $kategori->nama_kategori }}.
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>

    </div>
</x-filament-panels::page>