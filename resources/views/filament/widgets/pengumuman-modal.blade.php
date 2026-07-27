<div class="space-y-4">
    <!-- Header Modal Kustom -->
    <div class="text-sm text-gray-500 border-b border-gray-200 dark:border-gray-700 pb-3">
        Dipublikasikan oleh <strong class="text-gray-900 dark:text-white">{{ $penulis }}</strong> pada {{ $tanggal }}
    </div>
    
    <!-- Isi Modal yang mempertahankan format HTML (Tebal, Miring, Paragraf, dll) dari RichEditor -->
    <div class="prose dark:prose-invert max-w-none text-sm leading-relaxed">
        {!! $isi !!}
    </div>
</div>