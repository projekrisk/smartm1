<div class="flex flex-col gap-4 bg-slate-50 dark:bg-slate-900 p-4 md:p-6 rounded-xl border border-gray-200 dark:border-gray-800 w-full max-h-[600px] overflow-y-auto">
    
    <!-- Lencana Status Tiket (Lebih Normal dan Elegan) -->
    <div class="flex justify-center mb-2">
        <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 text-xs font-medium py-1 px-4 rounded-full shadow-sm border border-blue-200 dark:border-blue-800/50">
            Status Sesi: {{ $getRecord()->status }}
        </span>
    </div>

    <!-- Looping Isi Percakapan -->
    @foreach($getRecord()->details as $chat)
        @if($chat->pengirim === 'Siswa')
            <!-- BUBBLE KIRI (Siswa) - Warna Putih -->
            <div class="flex justify-start">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl rounded-tl-sm px-4 pt-2.5 pb-2 max-w-[85%] md:max-w-md shadow-sm min-w-[150px]">
                    <div class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1">
                        {{ $getRecord()->siswa->nama_lengkap }}
                    </div>
                    <div class="text-[13px] text-gray-800 dark:text-gray-200 leading-snug break-words">
                        {{ $chat->pesan }}
                    </div>
                    <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-1.5 text-right font-medium">
                        {{ $chat->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @else
            <!-- BUBBLE KANAN (Admin) - Warna Hijau Muda / Emerald -->
            <div class="flex justify-end">
                <div class="bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl rounded-tr-sm px-4 pt-2.5 pb-2 max-w-[85%] md:max-w-md shadow-sm min-w-[150px]">
                    <div class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 mb-1">
                        Admin ({{ $chat->user->name ?? '-' }})
                    </div>
                    <div class="text-[13px] text-gray-800 dark:text-gray-100 leading-snug break-words">
                        {{ $chat->pesan }}
                    </div>
                    <div class="text-[10px] text-emerald-600/70 dark:text-emerald-400/70 mt-1.5 text-right font-medium">
                        {{ $chat->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    
    <!-- INDIKATOR SESI DITUTUP (Tampil jika tiket Selesai) -->
    @if($getRecord()->status === 'Selesai')
        <div class="mt-4 mb-2 flex justify-center">
            <span class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-medium py-1.5 px-4 rounded-full border border-red-200 dark:border-red-800/50 shadow-sm flex items-center gap-1.5">
                <x-filament::icon icon="heroicon-s-lock-closed" class="w-3.5 h-3.5" />
                Sesi Percakapan Telah Ditutup
            </span>
        </div>
    @endif

</div>