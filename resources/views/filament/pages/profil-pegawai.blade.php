<x-filament-panels::page>
    @if($pegawai)
        <!-- MEMBUAT SISTEM TAB MENGGUNAKAN ALPINE.JS -->
        <div x-data="{ activeTab: 'informasi' }" class="space-y-6">
            
            <!-- HEADER TAB -->
            <x-filament::tabs label="Menu Profil">
                <x-filament::tabs.item 
                    alpine-active="activeTab === 'informasi'" 
                    x-on:click="activeTab = 'informasi'" 
                    icon="heroicon-m-user">
                    Informasi Data Pokok
                </x-filament::tabs.item>
                <x-filament::tabs.item 
                    alpine-active="activeTab === 'foto'" 
                    x-on:click="activeTab = 'foto'" 
                    icon="heroicon-m-photo">
                    Ubah Foto Profil
                </x-filament::tabs.item>
                <x-filament::tabs.item 
                    alpine-active="activeTab === 'password'" 
                    x-on:click="activeTab = 'password'" 
                    icon="heroicon-m-lock-closed">
                    Keamanan & Password
                </x-filament::tabs.item>
            </x-filament::tabs>

            <!-- KONTEN TAB 1: INFORMASI PROFIL -->
            <div x-show="activeTab === 'informasi'" x-cloak class="space-y-6">
                <x-filament::section>
                    <x-slot name="heading">Informasi Data Pokok Pegawai</x-slot>
                    <x-slot name="description">Data ini terintegrasi dengan sistem kepegawaian. Hubungi staf Admin jika terdapat kesalahan data.</x-slot>
                    <div class="mt-4">
                        {{ $this->infolist }}
                    </div>
                </x-filament::section>
            </div>

            <!-- KONTEN TAB 2: UBAH FOTO PROFIL -->
            <div x-show="activeTab === 'foto'" x-cloak class="space-y-6">
                <!-- max-w-xl mx-auto berfungsi untuk mengurung dan meletakkan kotak ke tengah layar -->
                <x-filament::section class="max-w-xl mx-auto">
                    <x-slot name="heading">Foto Profil</x-slot>
                    <x-slot name="description">Foto ini akan tampil di menu pojok kanan atas dan dashboard Anda.</x-slot>
                    
                    <form wire:submit="simpanFoto" class="mt-4">
                        <!-- Trik CSS tambahan untuk memastikan semua elemen Filament di dalamnya berada di tengah -->
                        <div class="flex justify-center w-full mb-6 [&_.fi-fo-field-wrp]:mx-auto [&_.fi-fo-field-wrp]:text-center [&_.fi-fo-file-upload]:mx-auto">
                            {{ $this->fotoForm }}
                        </div>
                        <div class="text-center">
                            <x-filament::button type="submit" color="primary" size="md" icon="heroicon-o-arrow-up-tray" class="w-full">
                                Simpan Foto Baru
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            </div>

            <!-- KONTEN TAB 3: GANTI PASSWORD -->
            <div x-show="activeTab === 'password'" x-cloak class="space-y-6">
                <x-filament::section class="max-w-xl mx-auto">
                    <x-slot name="heading">Keamanan Akun</x-slot>
                    <x-slot name="description">Pastikan menggunakan kombinasi angka dan huruf yang kuat dan unik.</x-slot>
                    
                    <form wire:submit="simpanPassword" class="mt-4 space-y-4">
                        {{ $this->passwordForm }}
                        <div class="pt-4 text-right">
                            <x-filament::button type="submit" color="danger" icon="heroicon-o-key" class="w-full">
                                Update Password
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            </div>
            
        </div>
    @else
        <!-- Tampilan khusus jika login menggunakan akun Super Admin yang tidak punya profil Pegawai -->
        <x-filament::section>
            <div class="text-center text-gray-500 py-10">
                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-12 h-12 mx-auto text-warning-500 mb-4" />
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Data Pegawai Tidak Ditemukan</h2>
                <p class="mt-2">Akun ini tidak tertaut dengan data Pegawai manapun di sistem (Mode Super Admin).</p>
            </div>
        </x-filament::section>
        
        <x-filament::section class="max-w-xl mx-auto mt-6">
            <x-slot name="heading">Keamanan Akun Admin</x-slot>
            <form wire:submit="simpanPassword" class="mt-4 space-y-4">
                {{ $this->passwordForm }}
                <div class="pt-4 text-right">
                    <x-filament::button type="submit" color="danger" class="w-full">Update Password</x-filament::button>
                </div>
            </form>
        </x-filament::section>
    @endif
</x-filament-panels::page>