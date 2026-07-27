<x-filament-panels::page>
    <x-filament-panels::form wire:submit="simpan">
        
        {{ $this->form }}

        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit" color="primary" size="lg" icon="heroicon-o-check-circle">
                Simpan Pengaturan
            </x-filament::button>
        </div>
        
    </x-filament-panels::form>
</x-filament-panels::page>