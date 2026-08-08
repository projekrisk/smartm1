<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-2 mb-4">
            <x-filament::icon icon="heroicon-o-users" class="w-5 h-5 text-gray-500" />
            <h3 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                Rincian Pegawai
            </h3>
        </div>
        
        <div class="overflow-x-auto ring-1 ring-gray-200 dark:ring-white/10 rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-white/5 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold w-1/2">Kategori Rincian</th>
                        <th scope="col" class="px-6 py-3 text-center font-semibold text-blue-600 dark:text-blue-400">Laki-laki</th>
                        <th scope="col" class="px-6 py-3 text-center font-semibold text-pink-600 dark:text-pink-400">Perempuan</th>
                        <th scope="col" class="px-6 py-3 text-center font-bold text-primary-600 dark:text-primary-400 border-l border-gray-200 dark:border-gray-700">Total</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    
                    <tr class="bg-gray-100/50 dark:bg-gray-800/50">
                        <td colspan="4" class="px-6 py-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Berdasarkan Tugas (Jenis PTK)
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • Kepala Sekolah
                        </th>
                        <td class="px-6 py-3 text-center">{{ $kepsekL }}</td>
                        <td class="px-6 py-3 text-center">{{ $kepsekP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $kepsek }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • Guru / Pendidik
                        </th>
                        <td class="px-6 py-3 text-center">{{ $guruL }}</td>
                        <td class="px-6 py-3 text-center">{{ $guruP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $guru }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • Tenaga Kependidikan
                        </th>
                        <td class="px-6 py-3 text-center">{{ $tendikL }}</td>
                        <td class="px-6 py-3 text-center">{{ $tendikP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $tendik }}</td>
                    </tr>

                    <tr class="bg-gray-100/50 dark:bg-gray-800/50 border-t-2 border-gray-200 dark:border-gray-700">
                        <td colspan="4" class="px-6 py-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Berdasarkan Status Kepegawaian
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • Pegawai Negeri Sipil (PNS)
                        </th>
                        <td class="px-6 py-3 text-center">{{ $pnsL }}</td>
                        <td class="px-6 py-3 text-center">{{ $pnsP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $pns }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • PPPK
                        </th>
                        <td class="px-6 py-3 text-center">{{ $pppkL }}</td>
                        <td class="px-6 py-3 text-center">{{ $pppkP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $pppk }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • Tenaga Honorer / Sukarelawan
                        </th>
                        <td class="px-6 py-3 text-center">{{ $honorerL }}</td>
                        <td class="px-6 py-3 text-center">{{ $honorerP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $honorer }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white ml-4 block">
                            • GTY / PTY (Yayasan)
                        </th>
                        <td class="px-6 py-3 text-center">{{ $gtyL }}</td>
                        <td class="px-6 py-3 text-center">{{ $gtyP }}</td>
                        <td class="px-6 py-3 text-center font-bold border-l border-gray-200 dark:border-gray-700">{{ $gty }}</td>
                    </tr>
                </tbody>

                <tfoot class="bg-primary-50 dark:bg-primary-900/20 border-t-2 border-primary-200 dark:border-primary-800">
                    <tr>
                        <th scope="row" class="px-6 py-4 text-base font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                            TOTAL KESELURUHAN PEGAWAI
                        </th>
                        <td class="px-6 py-4 text-center font-bold text-blue-700 dark:text-blue-400 text-base">{{ $totalL }}</td>
                        <td class="px-6 py-4 text-center font-bold text-pink-700 dark:text-pink-400 text-base">{{ $totalP }}</td>
                        <td class="px-6 py-4 text-center font-extrabold text-primary-700 dark:text-primary-400 text-lg border-l border-primary-200 dark:border-primary-800">
                            {{ $grandTotal }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>