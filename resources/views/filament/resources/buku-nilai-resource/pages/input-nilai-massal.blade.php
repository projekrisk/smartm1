<x-filament-panels::page>
    <!-- Memanggil tag Form dan menghubungkannya dengan fungsi simpan() -->
    <x-filament-panels::form wire:submit="simpan">
        
        {{ $this->form }}

        <!-- Tombol Aksi di Bawah Form -->
        <div class="mt-6 flex justify-end gap-x-4">
            <x-filament::button 
                type="button" 
                color="gray" 
                tag="a" 
                href="{{ \App\Filament\Resources\BukuNilaiResource::getUrl('index') }}">
                Batal / Kembali
            </x-filament::button>

            <x-filament::button type="submit" color="primary">
                Simpan Semua Nilai
            </x-filament::button>
        </div>
        
    </x-filament-panels::form>
</x-filament-panels::page>