<x-filament-panels::page>
    @if($pegawai)
        <!-- 🌟 PROFIL HEADER CARD -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden mb-6">
            <!-- Banner Background -->
            <div class="h-32 bg-gradient-to-r from-primary-500 to-primary-700 dark:from-primary-600 dark:to-primary-900"></div>
            
            <div class="px-6 pb-6 relative">
                <div class="-mt-12 sm:-mt-16 sm:flex sm:items-end sm:space-x-5">
                    <!-- Foto Profil Melingkar -->
                    <div class="relative w-24 h-24 sm:w-32 sm:h-32 rounded-full ring-4 ring-white dark:ring-gray-900 bg-gray-100 dark:bg-gray-800 overflow-hidden flex-shrink-0">
                        @if($pegawai->foto)
                            <img src="{{ url('/uploads/' . $pegawai->foto) }}" alt="Foto Pegawai" class="w-full h-full object-cover">
                        @else
                            <x-filament::icon icon="heroicon-m-user" class="w-full h-full text-gray-400 p-4" />
                        @endif
                    </div>
                    
                    <!-- Nama dan Jabatan Utama -->
                    <div class="mt-4 sm:mt-0 sm:flex-1 sm:pb-2">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white truncate">
                            {{ $pegawai->nama_lengkap }}
                        </h1>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6 text-sm font-medium text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1.5 mt-1 sm:mt-0">
                                <x-filament::icon icon="heroicon-m-briefcase" class="w-4 h-4 text-primary-500" />
                                {{ $pegawai->jabatan ?? 'Pegawai/Guru' }}
                            </div>
                            <div class="flex items-center gap-1.5 mt-1 sm:mt-0">
                                <x-filament::icon icon="heroicon-m-identification" class="w-4 h-4 text-primary-500" />
                                NIP: {{ $pegawai->nip ?? '-' }}
                            </div>
                            <div class="flex items-center gap-1.5 mt-1 sm:mt-0">
                                <x-filament::icon icon="heroicon-m-check-badge" class="w-4 h-4 text-success-500" />
                                {{ $pegawai->status_pegawai ?? 'Aktif' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

            <!-- KONTEN TAB 1: INFORMASI PROFIL -->
            <div x-show="activeTab === 'informasi'" x-cloak class="space-y-6">
                <!-- Tidak perlu section tambahan di sini karena infolist di class PHP sudah kita buat menggunakan Infolist Sections -->
                {{ $this->infolist }}
            </div>

            <!-- KONTEN TAB 2: UBAH FOTO PROFIL -->
            <div x-show="activeTab === 'foto'" x-cloak class="space-y-6">
                <x-filament::section class="max-w-xl mx-auto">
                    <x-slot name="heading">Foto Profil</x-slot>
                    <x-slot name="description">Foto ini akan tampil di menu pojok kanan atas, dokumen cetak, dan dashboard Anda.</x-slot>
                    
                    <form wire:submit="simpanFoto" class="mt-4">
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
                <x-filament::section class="max-w-xl mx-auto border-t-4 border-t-danger-500">
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
        <!-- Tampilan khusus jika login menggunakan akun Super Admin -->
        <x-filament::section>
            <div class="text-center text-gray-500 py-10">
                <x-filament::icon icon="heroicon-o-shield-exclamation" class="w-16 h-16 mx-auto text-warning-500 mb-4" />
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Akses Super Admin</h2>
                <p class="mt-2">Akun tingkat atas ini tidak tertaut dengan buku induk Pegawai manapun di sistem.</p>
            </div>
        </x-filament::section>
        
        <x-filament::section class="max-w-xl mx-auto mt-6 border-t-4 border-t-danger-500">
            <x-slot name="heading">Keamanan Akun Admin</x-slot>
            <form wire:submit="simpanPassword" class="mt-4 space-y-4">
                {{ $this->passwordForm }}
                <div class="pt-4 text-right">
                    <x-filament::button type="submit" color="danger" icon="heroicon-o-key" class="w-full">Update Password</x-filament::button>
                </div>
            </form>
        </x-filament::section>
    @endif
</x-filament-panels::page>