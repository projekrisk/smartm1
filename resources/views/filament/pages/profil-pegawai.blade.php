<x-filament-panels::page>
    @if($pegawai)
        <!-- SISTEM TAB MENGGUNAKAN ALPINE.JS -->
        <div x-data="{ activeTab: 'informasi' }" class="space-y-6">
            
            <!-- HEADER TAB -->
            <x-filament::tabs label="Menu Profil">
                <x-filament::tabs.item 
                    alpine-active="activeTab === 'informasi'" 
                    x-on:click="activeTab = 'informasi'" 
                    icon="heroicon-m-document-text">
                    Informasi Lengkap
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

            <!-- KONTEN TAB 1: INFORMASI PROFIL LENGKAP -->
            <div x-show="activeTab === 'informasi'" x-cloak class="animate-fade-in">
                <!-- Foto & Nama kini sudah diraster rapi di dalam PHP Infolist -->
                {{ $this->infolist }}
            </div>

            <!-- KONTEN TAB 2: UBAH FOTO PROFIL -->
            <div x-show="activeTab === 'foto'" x-cloak class="animate-fade-in pt-2">
                <!-- 🌟 x-filament::section dengan atribut "aside" akan otomatis membelah layar (Teks di Kiri, Form di Kanan) dengan sangat rapi -->
                <x-filament::section aside>
                    <x-slot name="heading">Foto Profil Akun</x-slot>
                    <x-slot name="description">
                        Pilih foto terbaik Anda. Disarankan menggunakan foto dengan rasio 1:1 (persegi). Foto ini akan muncul di pojok kanan atas dasbor dan pada dokumen resmi cetakan sekolah.
                    </x-slot>
                    
                    <form wire:submit="simpanFoto" class="space-y-6">
                        {{ $this->fotoForm }}
                        
                        <div class="flex justify-end pt-4">
                            <x-filament::button type="submit" color="primary" icon="heroicon-o-arrow-up-tray">
                                Simpan Perubahan Foto
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            </div>

            <!-- KONTEN TAB 3: GANTI PASSWORD -->
            <div x-show="activeTab === 'password'" x-cloak class="animate-fade-in pt-2">
                <x-filament::section aside>
                    <x-slot name="heading">Keamanan & Sandi</x-slot>
                    <x-slot name="description">
                        Pastikan akun Anda tetap aman dengan menggunakan kata sandi yang panjang dan unik. <br><br>
                        Sangat disarankan untuk <b>tidak</b> menggunakan NIP, NIK, atau Tanggal Lahir sebagai kata sandi demi menghindari akses yang tidak sah.
                    </x-slot>
                    
                    <form wire:submit="simpanPassword" class="space-y-6">
                        {{ $this->passwordForm }}
                        
                        <div class="flex justify-end pt-4">
                            <x-filament::button type="submit" color="danger" icon="heroicon-o-lock-closed">
                                Perbarui Kata Sandi
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            </div>
            
        </div>
    @else
        <!-- TAMPILAN KHUSUS UNTUK SUPER ADMIN -->
        <x-filament::section>
            <div class="text-center text-gray-500 py-10">
                <x-filament::icon icon="heroicon-o-shield-exclamation" class="w-16 h-16 mx-auto text-warning-500 mb-4" />
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Akses Super Admin Tingkat Atas</h2>
                <p class="mt-2">Akun ini adalah Master System dan tidak tertaut dengan data Profil Pegawai manapun di sekolah.</p>
            </div>
        </x-filament::section>
        
        <div class="mt-8">
            <x-filament::section aside>
                <x-slot name="heading">Keamanan Akun Admin</x-slot>
                <x-slot name="description">
                    Jaga kerahasiaan kata sandi Super Admin. Akun ini memegang kendali penuh atas seluruh data siswa, guru, dan staf sekolah.
                </x-slot>

                <form wire:submit="simpanPassword" class="space-y-6">
                    {{ $this->passwordForm }}
                    
                    <div class="flex justify-end pt-4">
                        <x-filament::button type="submit" color="danger" icon="heroicon-o-shield-check">
                            Update Password Admin
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>
        </div>
    @endif

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-filament-panels::page>