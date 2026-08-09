<x-filament-panels::page>
    @if($pegawai)
        <!-- 🌟 PROFIL HEADER CARD (TETAP DIPERTAHANKAN KARENA SUDAH RAPI) -->
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
            <div x-show="activeTab === 'informasi'" x-cloak class="space-y-6 animate-fade-in">
                {{ $this->infolist }}
            </div>

            <!-- 🌟 KONTEN TAB 2: UBAH FOTO PROFIL (LAYOUT BARU) -->
            <div x-show="activeTab === 'foto'" x-cloak class="animate-fade-in pt-4">
                <div class="md:flex md:gap-8">
                    <!-- Penjelasan Kiri -->
                    <div class="md:w-1/3 mb-6 md:mb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Foto Profil Akun</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Pilih foto terbaik Anda. Disarankan menggunakan foto dengan rasio 1:1 (persegi) agar tidak terpotong. Foto ini akan muncul di pojok kanan atas dasbor dan dokumen resmi cetakan sekolah.
                        </p>
                    </div>
                    
                    <!-- Kartu Form Kanan -->
                    <div class="md:w-2/3">
                        <x-filament::section>
                            <form wire:submit="simpanFoto" class="space-y-6">
                                <!-- Layout natural bawaan Filament (tanpa paksaan CSS) -->
                                <div class="w-full">
                                    {{ $this->fotoForm }}
                                </div>
                                
                                <div class="flex justify-end pt-5 border-t border-gray-200 dark:border-white/10 mt-6">
                                    <x-filament::button type="submit" color="primary" icon="heroicon-o-arrow-up-tray">
                                        Simpan Perubahan Foto
                                    </x-filament::button>
                                </div>
                            </form>
                        </x-filament::section>
                    </div>
                </div>
            </div>

            <!-- 🌟 KONTEN TAB 3: GANTI PASSWORD (LAYOUT BARU) -->
            <div x-show="activeTab === 'password'" x-cloak class="animate-fade-in pt-4">
                <div class="md:flex md:gap-8">
                    <!-- Penjelasan Kiri -->
                    <div class="md:w-1/3 mb-6 md:mb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Keamanan & Sandi</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Pastikan akun Anda tetap aman dengan menggunakan kata sandi yang panjang dan acak. <br><br>
                            Sangat disarankan untuk <b>tidak</b> menggunakan NIP, NIK, atau Tanggal Lahir sebagai kata sandi demi menghindari akses yang tidak sah.
                        </p>
                    </div>
                    
                    <!-- Kartu Form Kanan -->
                    <div class="md:w-2/3">
                        <x-filament::section>
                            <form wire:submit="simpanPassword" class="space-y-6">
                                <div class="w-full">
                                    {{ $this->passwordForm }}
                                </div>
                                
                                <div class="flex justify-end pt-5 border-t border-gray-200 dark:border-white/10 mt-6">
                                    <x-filament::button type="submit" color="danger" icon="heroicon-o-lock-closed">
                                        Perbarui Kata Sandi
                                    </x-filament::button>
                                </div>
                            </form>
                        </x-filament::section>
                    </div>
                </div>
            </div>
            
        </div>
    @else
        <!-- Tampilan khusus jika login menggunakan akun Super Admin -->
        <x-filament::section>
            <div class="text-center text-gray-500 py-10">
                <x-filament::icon icon="heroicon-o-shield-exclamation" class="w-16 h-16 mx-auto text-warning-500 mb-4" />
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Akses Super Admin Tingkat Atas</h2>
                <p class="mt-2">Akun ini adalah Master System dan tidak tertaut dengan data Profil Pegawai manapun di sekolah.</p>
            </div>
        </x-filament::section>
        
        <!-- Form Keamanan Super Admin Menggunakan Aside Layout -->
        <div class="md:flex md:gap-8 mt-10">
            <div class="md:w-1/3 mb-6 md:mb-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Keamanan Akun Admin</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Jaga kerahasiaan kata sandi Super Admin. Akun ini memegang kendali penuh atas seluruh data siswa, guru, dan staf sekolah.
                </p>
            </div>
            <div class="md:w-2/3">
                <x-filament::section>
                    <form wire:submit="simpanPassword" class="space-y-6">
                        <div class="w-full">
                            {{ $this->passwordForm }}
                        </div>
                        <div class="flex justify-end pt-5 border-t border-gray-200 dark:border-white/10 mt-6">
                            <x-filament::button type="submit" color="danger" icon="heroicon-o-shield-check">
                                Update Password Admin
                            </x-filament::button>
                        </div>
                    </form>
                </x-filament::section>
            </div>
        </div>
    @endif

    <!-- Animasi transisi halus saat pindah tab -->
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