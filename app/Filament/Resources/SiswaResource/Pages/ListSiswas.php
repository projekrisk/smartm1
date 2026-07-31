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
                ->visible(fn () => Auth::user()->peran === 'admin')
                ->action(function () {
                    $headers = ['nis', 'nisn', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'nik', 'no_kk', 'agama', 'telepon', 'email', 'alamat', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'lintang', 'bujur', 'nama_ayah', 'telepon_ayah', 'nama_ibu', 'telepon_ibu', 'nama_wali', 'telepon_wali', 'sekolah_asal', 'jalur_masuk', 'tanggal_masuk', 'nama_kelas'];
                    $csvData = implode(',', $headers) . "\n";
                    
                    return response()->streamDownload(function () use ($csvData) {
                        echo $csvData;
                    }, 'template_impor_siswa.csv', ['Content-Type' => 'text/csv']);
                }),

            Actions\Action::make('impor_csv')
                ->label('Impor')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn () => Auth::user()->peran === 'admin')
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
                    $nisTerbaca = [];
                    
                    \Illuminate\Support\Facades\DB::beginTransaction();

                    try {
                        while (($row = fgetcsv($file)) !== false) {
                            if (count($headers) !== count($row)) { $gagal++; continue; }
                            $rawRowData = array_combine($headers, $row);
                            
                            $rowData = [];
                            foreach ($rawRowData as $key => $value) {
                                $cleanVal = ltrim(trim((string) $value), "'");
                                
                                $rowData[$key] = ($cleanVal === '' || $cleanVal === '-' || strtolower($cleanVal) === 'null' || strtolower($cleanVal) === 'n/a') ? null : $cleanVal;
                            }
                            
                            $nisn = $rowData['nisn'];
                            $nis = $rowData['nis'];
                            
                            if (empty($nisn) && empty($nis)) { $gagal++; continue; }

                            $duplikatNisn = !empty($nisn) && in_array($nisn, $nisnTerbaca);
                            $duplikatNis = !empty($nis) && in_array($nis, $nisTerbaca);
                            
                            if ($duplikatNisn || $duplikatNis) { 
                                $gagal++; 
                                continue; 
                            }
                            
                            if (!empty($nisn)) $nisnTerbaca[] = $nisn;
                            if (!empty($nis)) $nisTerbaca[] = $nis;
                            
                            $inputKelas = $rowData['nama_kelas'] ?? ($rowData['kelas_id'] ?? null);
                            if (empty($inputKelas)) { $gagal++; continue; }

                            $kelasId = null;
                            
                            if (is_numeric($inputKelas)) {
                                $cekKelas = \App\Models\Kelas::find($inputKelas);
                                if ($cekKelas) {
                                    $kelasId = $cekKelas->id;
                                }
                            }

                            if (!$kelasId) {
                                $kelasBaru = \App\Models\Kelas::firstOrCreate(['nama_kelas' => $inputKelas]);
                                $kelasId = $kelasBaru->id;
                            }

                            $jkRaw = strtolower($rowData['jenis_kelamin'] ?? '');
                            $jkFix = null;
                            if (in_array($jkRaw, ['l', 'laki-laki', 'laki laki', 'laki'])) $jkFix = 'Laki-laki';
                            elseif (in_array($jkRaw, ['p', 'perempuan', 'wanita'])) $jkFix = 'Perempuan';

                            $dataSimpan = [
                                'nis' => $nis,
                                'nisn' => $nisn,
                                'nama_lengkap' => $rowData['nama_lengkap'],
                                'jenis_kelamin' => $jkFix,
                                'tempat_lahir' => $rowData['tempat_lahir'],
                                'tanggal_lahir' => empty($rowData['tanggal_lahir']) ? null : date('Y-m-d', strtotime($rowData['tanggal_lahir'])),
                                'nik' => $rowData['nik'],
                                'no_kk' => $rowData['no_kk'],
                                'agama' => $rowData['agama'],
                                'telepon' => $rowData['telepon'],
                                'email' => $rowData['email'],
                                'alamat' => $rowData['alamat'],
                                'rt' => $rowData['rt'],
                                'rw' => $rowData['rw'],
                                'kelurahan' => $rowData['kelurahan'],
                                'kecamatan' => $rowData['kecamatan'],
                                'kabupaten' => $rowData['kabupaten'],
                                'lintang' => $rowData['lintang'],
                                'bujur' => $rowData['bujur'],
                                'nama_ayah' => $rowData['nama_ayah'],
                                'telepon_ayah' => $rowData['telepon_ayah'],
                                'nama_ibu' => $rowData['nama_ibu'],
                                'telepon_ibu' => $rowData['telepon_ibu'],
                                'nama_wali' => $rowData['nama_wali'],
                                'telepon_wali' => $rowData['telepon_wali'],
                                'sekolah_asal' => $rowData['sekolah_asal'],
                                'jalur_masuk' => $rowData['jalur_masuk'] ?? 'Siswa Baru',
                                'tanggal_masuk' => empty($rowData['tanggal_masuk']) ? null : date('Y-m-d', strtotime($rowData['tanggal_masuk'])),
                                'kelas_id' => $kelasId,
                            ];

                            $siswaAda = \App\Models\Siswa::where(function($query) use ($nis, $nisn) {
                                if (!empty($nisn)) $query->where('nisn', $nisn);
                                if (!empty($nis)) $query->orWhere('nis', $nis);
                            })->first();

                            if ($siswaAda) {
                                $siswaAda->update($dataSimpan);
                            } else {
                                \App\Models\Siswa::create($dataSimpan);
                            }
                            
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
                ->visible(fn () => Auth::user()->peran === 'admin')
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

            Actions\Action::make('ekspor_cepat')
                ->label('Ekspor')
                ->color('warning')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn () => Auth::user()->peran === 'admin')
                ->action(function () {
                    set_time_limit(0);
                    
                    $headers = ['nis', 'nisn', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'nik', 'no_kk', 'agama', 'telepon', 'email', 'alamat', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'lintang', 'bujur', 'nama_ayah', 'telepon_ayah', 'nama_ibu', 'telepon_ibu', 'nama_wali', 'telepon_wali', 'sekolah_asal', 'jalur_masuk', 'tanggal_masuk', 'nama_kelas'];
                    
                    $callback = function () use ($headers) {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $headers);

                        $siswas = \App\Models\Siswa::with('kelas')->orderBy('kelas_id')->orderBy('nama_lengkap')->get();

                        foreach ($siswas as $siswa) {
                            fputcsv($file, [
                                $siswa->nis ? "'" . $siswa->nis : '',
                                $siswa->nisn ? "'" . $siswa->nisn : '',
                                $siswa->nama_lengkap,
                                $siswa->jenis_kelamin,
                                $siswa->tempat_lahir,
                                $siswa->tanggal_lahir,
                                $siswa->nik ? "'" . $siswa->nik : '',
                                $siswa->no_kk ? "'" . $siswa->no_kk : '',
                                $siswa->agama,
                                $siswa->telepon ? "'" . $siswa->telepon : '',
                                $siswa->email,
                                $siswa->alamat,
                                $siswa->rt,
                                $siswa->rw,
                                $siswa->kelurahan,
                                $siswa->kecamatan,
                                $siswa->kabupaten,
                                $siswa->lintang,
                                $siswa->bujur,
                                $siswa->nama_ayah,
                                $siswa->telepon_ayah ? "'" . $siswa->telepon_ayah : '',
                                $siswa->nama_ibu,
                                $siswa->telepon_ibu ? "'" . $siswa->telepon_ibu : '',
                                $siswa->nama_wali,
                                $siswa->telepon_wali ? "'" . $siswa->telepon_wali : '',
                                $siswa->sekolah_asal,
                                $siswa->jalur_masuk,
                                $siswa->tanggal_masuk,
                                $siswa->kelas->nama_kelas ?? '',
                            ]);
                        }
                        fclose($file);
                    };

                    $fileName = 'Ekspor_Siswa_' . date('Y-m-d_H-i') . '.csv';

                    return response()->stream($callback, 200, [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ]);
                }),

            Actions\CreateAction::make()
                ->label('Siswa Baru')
                ->visible(fn () => Auth::user()->peran === 'admin'),
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