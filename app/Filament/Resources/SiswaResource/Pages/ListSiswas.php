<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('unduh_template')
                ->label('Template')
                ->color('info')
                ->icon('heroicon-o-document-arrow-down')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->action(function () {
                    $headers = ['nis', 'nisn', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'nik', 'no_kk', 'agama', 'telepon', 'email', 'alamat', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'lintang', 'bujur', 'nama_ayah', 'telepon_ayah', 'nama_ibu', 'telepon_ibu', 'nama_wali', 'telepon_wali', 'sekolah_asal', 'jalur_masuk', 'tanggal_masuk', 'kelas_id'];
                    $csvData = implode(',', $headers) . "\n";
                    
                    return response()->streamDownload(function () use ($csvData) {
                        echo $csvData;
                    }, 'template_impor_siswa.csv', ['Content-Type' => 'text/csv']);
                }),

            Actions\Action::make('impor_csv')
                ->label('Impor')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Upload File CSV')
                        ->disk('local')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values'])
                        ->required()
                        ->helperText('Gunakan file berformat CSV. Baris pertama harus berisi judul kolom sesuai template.'),
                ])
                ->action(function (array $data) {
                    set_time_limit(0);
                    ini_set('memory_limit', '512M');

                    $filePath = \Illuminate\Support\Facades\Storage::disk('local')->path($data['file']);
                    if (!file_exists($filePath)) {
                        \Filament\Notifications\Notification::make()->title('Gagal: File tidak ditemukan')->danger()->send();
                        return;
                    }

                    $file = fopen($filePath, 'r');
                    
                    $headers = fgetcsv($file);
                    if (isset($headers[0])) $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
                    $headers = array_map(function($val) { return strtolower(trim($val)); }, $headers);

                    $berhasil = 0; 
                    $gagal = 0; 
                    $nisnTerbaca = [];
                    
                    \Illuminate\Support\Facades\DB::beginTransaction();

                    try {
                        while (($row = fgetcsv($file)) !== false) {
                            if (count($headers) !== count($row)) { $gagal++; continue; }
                            $rowData = array_combine($headers, $row);
                            
                            $nisn = trim($rowData['nisn'] ?? '');
                            if (empty($nisn) || in_array($nisn, $nisnTerbaca)) { $gagal++; continue; }
                            $nisnTerbaca[] = $nisn;
                            
                            $kelasId = trim($rowData['kelas_id'] ?? '');
                            if (empty($kelasId)) { $gagal++; continue; }

                            $jkRaw = strtolower(trim($rowData['jenis_kelamin'] ?? ''));
                            $jkFix = null;
                            if (in_array($jkRaw, ['l', 'laki-laki', 'laki laki', 'laki'])) $jkFix = 'Laki-laki';
                            elseif (in_array($jkRaw, ['p', 'perempuan', 'wanita'])) $jkFix = 'Perempuan';

                            \App\Models\Siswa::updateOrCreate(
                                ['nisn' => $nisn],
                                [
                                    'nis' => $rowData['nis'] ?? null,
                                    'nama_lengkap' => $rowData['nama_lengkap'] ?? null,
                                    'jenis_kelamin' => $jkFix,
                                    'tempat_lahir' => $rowData['tempat_lahir'] ?? null,
                                    'tanggal_lahir' => empty($rowData['tanggal_lahir']) ? null : date('Y-m-d', strtotime($rowData['tanggal_lahir'])),
                                    'nik' => $rowData['nik'] ?? null,
                                    'no_kk' => $rowData['no_kk'] ?? null,
                                    'agama' => $rowData['agama'] ?? null,
                                    'telepon' => $rowData['telepon'] ?? null,
                                    'email' => $rowData['email'] ?? null,
                                    'alamat' => $rowData['alamat'] ?? null,
                                    'rt' => $rowData['rt'] ?? null,
                                    'rw' => $rowData['rw'] ?? null,
                                    'kelurahan' => $rowData['kelurahan'] ?? null,
                                    'kecamatan' => $rowData['kecamatan'] ?? null,
                                    'kabupaten' => $rowData['kabupaten'] ?? null,
                                    'lintang' => $rowData['lintang'] ?? null,
                                    'bujur' => $rowData['bujur'] ?? null,
                                    'nama_ayah' => $rowData['nama_ayah'] ?? null,
                                    'telepon_ayah' => $rowData['telepon_ayah'] ?? null,
                                    'nama_ibu' => $rowData['nama_ibu'] ?? null,
                                    'telepon_ibu' => $rowData['telepon_ibu'] ?? null,
                                    'nama_wali' => $rowData['nama_wali'] ?? null,
                                    'telepon_wali' => $rowData['telepon_wali'] ?? null,
                                    'sekolah_asal' => $rowData['sekolah_asal'] ?? null,
                                    'jalur_masuk' => $rowData['jalur_masuk'] ?? 'Siswa Baru',
                                    'tanggal_masuk' => empty($rowData['tanggal_masuk']) ? null : date('Y-m-d', strtotime($rowData['tanggal_masuk'])),
                                    'kelas_id' => $kelasId,
                                ]
                            );
                            $berhasil++;
                        }
                        
                        \Illuminate\Support\Facades\DB::commit();

                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\DB::rollBack(); // Batalkan semua jika ada 1 error
                        \Filament\Notifications\Notification::make()->title('Gagal Impor')->body('Kesalahan: ' . $e->getMessage())->danger()->send();
                        fclose($file); 
                        return;
                    }
                    
                    fclose($file);
                    \Filament\Notifications\Notification::make()->title('Impor Selesai')->body("Berhasil memproses $berhasil data. Ditolak/Lewati: $gagal data.")->success()->send();
                })
                ->modalHeading('Impor Data Siswa Massal')
                ->modalSubmitActionLabel('Mulai Impor Sekarang'),

            Actions\Action::make('impor_foto')
                ->label('Foto')
                ->color('info')
                ->icon('heroicon-o-photo')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file_zip')
                        ->label('Upload File ZIP')
                        ->disk('local')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'multipart/x-zip'])
                        ->required()
                        ->helperText('Upload file .zip berisi foto siswa. Nama file foto HARUS menggunakan NIS atau NISN (Contoh: 10101.jpg atau 00123456.png).'),
                ])
                ->action(function (array $data) {
                    set_time_limit(0);
                    ini_set('memory_limit', '512M');
                    
                    $zipPath = \Illuminate\Support\Facades\Storage::disk('local')->path($data['file_zip']);
                    $zip = new \ZipArchive;
                    
                    if ($zip->open($zipPath) === TRUE) {
                        $extractPath = public_path('uploads/foto-siswa');
                        if (!file_exists($extractPath)) {
                            mkdir($extractPath, 0755, true);
                        }

                        $berhasil = 0;
                        $gagal = 0;

                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            $fileinfo = pathinfo($filename);

                            if (str_starts_with($fileinfo['basename'], '.') || empty($fileinfo['extension'])) {
                                continue;
                            }

                            $identifier = $fileinfo['filename']; 
                            
                            $siswa = \App\Models\Siswa::where('nis', $identifier)
                                ->orWhere('nisn', $identifier)
                                ->first();

                            if ($siswa) {
                                $newFileName = $identifier . '_' . time() . '.' . $fileinfo['extension'];
                                $destPath = $extractPath . '/' . $newFileName;
                                
                                file_put_contents($destPath, $zip->getFromIndex($i));

                                $siswa->updateQuietly(['foto' => 'foto-siswa/' . $newFileName]);
                                $berhasil++;
                            } else {
                                $gagal++;
                            }
                        }
                        $zip->close();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Impor Foto Selesai')
                            ->body("Berhasil menautkan $berhasil foto. Ditolak/Siswa tidak ditemukan: $gagal file.")
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal')
                            ->body('Tidak dapat membaca/membuka file ZIP. Pastikan file tidak corrupt.')
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading('Impor Foto Siswa (ZIP)')
                ->modalSubmitActionLabel('Mulai Ekstrak Foto'),

            Actions\ExportAction::make()
                ->exporter(\App\Filament\Exports\SiswaExporter::class)
                ->label('Ekspor')
                ->color('warning')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf'])),

            Actions\CreateAction::make()->label('Siswa Baru'),
        ];
    }

    public function getTabs(): array
    {
        if (Auth::user()->peran === 'guru') {
            return [];
        }

        return [
            'Siswa Aktif' => Tab::make('Siswa Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                }))
                ->badge(Siswa::where(function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                })->count())
                ->badgeColor('success'),
                
            'Alumni (Lulus)' => Tab::make('Alumni (Lulus)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_siswa', 'Lulus'))
                ->badge(Siswa::where('status_siswa', 'Lulus')->count())
                ->badgeColor('info'),
                
            'Keluar / Mutasi' => Tab::make('Keluar / Mutasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status_siswa', ['Mutasi Keluar', 'Dikeluarkan', 'Wafat']))
                ->badge(Siswa::whereIn('status_siswa', ['Mutasi Keluar', 'Dikeluarkan', 'Wafat'])->count())
                ->badgeColor('danger'),
                
            'Semua Data' => Tab::make('Semua Data'),
        ];
    }
}