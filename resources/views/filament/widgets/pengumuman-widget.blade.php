<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-4">
            
            <!-- Sisi Kiri: Ikon & Teks Pengumuman -->
            <!-- min-w-0 wajib ditambahkan agar fitur pemotong teks (truncate) bisa bekerja -->
            <div class="flex items-center gap-x-4 min-w-0">
                
                <!-- Ikon Lingkaran (Mirip seperti Avatar) -->
                <div class="p-2 bg-primary-500/10 rounded-full flex-shrink-0">
                    <x-filament::icon icon="heroicon-o-megaphone" class="w-6 h-6 text-primary-500" />
                </div>
                
                <!-- Judul dan Isi Singkat -->
                <div class="min-w-0">
                    @if($pengumumanTerbaru)
                        <!-- class "truncate" akan memotong teks yang kepanjangan menjadi 1 baris saja -->
                        <h2 class="text-base font-bold text-gray-900 dark:text-white truncate">
                            {{ $pengumumanTerbaru->judul }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate mt-0.5">
                            {!! strip_tags($pengumumanTerbaru->isi) !!}
                        </p>
                    @else
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Pengumuman</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada informasi terbaru saat ini.</p>
                    @endif
                </div>
            </div>
            
            <!-- Sisi Kanan: Tombol Action (Diletakkan di ujung) -->
            @if($pengumumanTerbaru)
                <div class="flex-shrink-0 ml-2">
                    {{ $this->bacaSelengkapnyaAction }}
                </div>
            @endif
            
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>