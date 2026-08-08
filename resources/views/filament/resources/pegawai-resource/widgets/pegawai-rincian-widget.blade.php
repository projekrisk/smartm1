<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-2 mb-4">
            <x-filament::icon icon="heroicon-o-users" class="w-5 h-5 text-gray-500" />
            <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                Rincian Pegawai Berdasarkan Jenis Kelamin
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-white/5 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold">Jenis PTK</th>
                        <th scope="col" class="px-6 py-3 text-center font-semibold text-blue-600 dark:text-blue-400">Laki-laki (L)</th>
                        <th scope="col" class="px-6 py-3 text-center font-semibold text-pink-600 dark:text-pink-400">Perempuan (P)</th>
                        <th scope="col" class="px-6 py-3 text-center font-bold text-primary-600 dark:text-primary-400">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Kepala Sekolah
                        </th>
                        <td class="px-6 py-4 text-center">{{ $kepsekL }}</td>
                        <td class="px-6 py-4 text-center">{{ $kepsekP }}</td>
                        <td class="px-6 py-4 text-center font-bold">{{ $kepsek }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Guru / Pendidik
                        </th>
                        <td class="px-6 py-4 text-center">{{ $guruL }}</td>
                        <td class="px-6 py-4 text-center">{{ $guruP }}</td>
                        <td class="px-6 py-4 text-center font-bold">{{ $guru }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Tenaga Kependidikan
                        </th>
                        <td class="px-6 py-4 text-center">{{ $tendikL }}</td>
                        <td class="px-6 py-4 text-center">{{ $tendikP }}</td>
                        <td class="px-6 py-4 text-center font-bold">{{ $tendik }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>