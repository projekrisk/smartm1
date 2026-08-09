<div class="space-y-4">
    <div class="text-sm text-gray-500 border-b border-gray-200 dark:border-gray-700 pb-3">
        Dipublikasikan oleh <strong class="text-gray-900 dark:text-white">{{ $penulis }}</strong> pada {{ $tanggal }}
    </div>
    
    <div class="prose dark:prose-invert max-w-none text-sm leading-relaxed">
        {!! $isi !!}
    </div>
</div>