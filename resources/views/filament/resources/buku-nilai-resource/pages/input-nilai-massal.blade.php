<x-filament-panels::page>
    <x-filament-panels::form wire:submit="simpan">
        
        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-x-4">
            <x-filament::button 
                type="button" 
                color="gray" 
                tag="a" 
                href="{{ \App\Filament\Resources\BukuNilaiResource::getUrl('index') }}">
                Batal / Kembali
            </x-filament::button>

            <x-filament::button type="submit" color="primary">
                Simpan Semua
            </x-filament::button>
        </div>
        
    </x-filament-panels::form>
</x-filament-panels::page>