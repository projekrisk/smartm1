<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Carbon\Carbon;

class ProfilPegawai extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    protected static ?string $title = 'Profil Saya';
    protected static string $view = 'filament.pages.profil-pegawai';
    
    protected static bool $shouldRegisterNavigation = false;

    public ?array $passwordData = [];
    public ?array $fotoData = [];
    
    public ?Pegawai $pegawai = null;

    public function mount(): void
    {
                $this->pegawai = Pegawai::where('user_id', Auth::id())->first();
        
        if ($this->pegawai) {
            $this->fotoForm->fill([
                'foto' => $this->pegawai->foto,
            ]);
        }
        $this->passwordForm->fill();
    }

    protected function getForms(): array
    {
        return [
            'passwordForm',
            'fotoForm',
        ];
    }

    public function fotoForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->hiddenLabel()
                    ->image()
                    ->avatar()
                    ->disk('publik_upload')
                    ->directory('foto-pegawai')
                    ->maxSize(2048)
                    ->helperText('Format JPG/PNG. Maksimal 2MB.')
                    ->extraAttributes([
                        'class' => 'mx-auto flex justify-center text-center',
                        'style' => 'display: flex; justify-content: center; align-items: center; text-align: center;'
                    ]),
            ])
            ->statePath('fotoData');
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Password Saat Ini')
                    ->password()
                    ->required()
                    ->currentPassword(),
                Forms\Components\TextInput::make('new_password')
                    ->label('Password Baru')
                    ->password()
                    ->required()
                    ->minLength(6)
                    ->same('new_password_confirmation'),
                Forms\Components\TextInput::make('new_password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->required(),
            ])
            ->statePath('passwordData');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->pegawai)
            ->schema([
                Tabs::make('Data Pegawai')
                    ->tabs([
                        Tab::make('Identitas')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Grid::make(2)->schema([
                                    ImageEntry::make('foto')
                                        ->label('Foto Profil')
                                        ->disk('publik_upload')
                                        ->circular()
                                        ->width(120)
                                        ->height(120)
                                        ->state(fn ($record) => $record->foto ? url('/uploads/' . $record->foto) : null)
                                        ->defaultImageUrl(asset('images/default-avatar.png'))
                                        ->columnSpanFull(),
                                    
                                    TextEntry::make('nama')
                                        ->label('Nama Lengkap')
                                        ->weight('bold')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->columnSpanFull(),
                                    TextEntry::make('nik')->label('NIK (Nomor Kependudukan)'),
                                    TextEntry::make('no_kk')->label('Nomor KK')->default('-'),
                                    TextEntry::make('npwp')->label('NPWP')->default('-'),
                                    TextEntry::make('jenis_kelamin')->label('Jenis Kelamin')->default('-'),
                                    TextEntry::make('tempat_lahir')->label('Tempat Lahir')->default('-'),
                                    
                                    TextEntry::make('tanggal_lahir')
                                        ->label('Tanggal Lahir')
                                        ->formatStateUsing(function ($state) {
                                            if (empty($state) || $state === '-') return '-';
                                            try { return Carbon::parse($state)->isoFormat('D MMMM Y'); } catch (\Exception $e) { return '-'; }
                                        }),
                                        
                                    TextEntry::make('telepon')->label('Nomor Telepon')->default('-'),
                                    TextEntry::make('email')->label('Email Aktif')->default('-'),
                                ]),
                                Grid::make(2)->schema([
                                    TextEntry::make('no_rekening')->label('Nomor Rekening')->default('-'),
                                    TextEntry::make('nama_bank')->label('Bank')->default('-'),
                                    TextEntry::make('alamat')
                                        ->label('Alamat Lengkap')
                                        ->getStateUsing(fn ($record) => $record->alamat ? $record->alamat . ' RT ' . ($record->rt ?? '-') . '/RW ' . ($record->rw ?? '-') . ', ' . ($record->kelurahan ?? '-') . ', Kec. ' . ($record->kecamatan ?? '-') . ', ' . ($record->kabupaten ?? '-') : '-')
                                        ->columnSpanFull(),
                                ])->extraAttributes(['class' => 'mt-4 border-t pt-4 border-gray-200 dark:border-white/10']),
                            ]),
                            
                        Tab::make('Kepegawaian')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('jenis_ptk')
                                        ->label('Jenis PTK')
                                        ->weight('bold')
                                        ->default('-'),
                                    TextEntry::make('status_kepegawaian')
                                        ->label('Status Kepegawaian')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'PNS' => 'success',
                                            'PPPK' => 'info',
                                            'GTY/PTY' => 'primary',
                                            'Honorer' => 'warning',
                                            default => 'gray',
                                        })
                                        ->default('-'),
                                    TextEntry::make('tugas_utama')->label('Tugas Utama')->default('-'),
                                    TextEntry::make('nip')->label('NIP')->default('-'),
                                    TextEntry::make('nuptk')->label('NUPTK')->default('-'),
                                    TextEntry::make('pangkat_golongan')->label('Pangkat / Gol. Ruang')->default('-'),
                                    TextEntry::make('jabatan')->label('Jabatan')->default('-'),
                                    TextEntry::make('status_tugas_saat_ini')
                                        ->label('Status Tugas Saat Ini (Termasuk Wali Kelas & Tambahan)')
                                        ->getStateUsing(function ($record) {
                                            $tugas = $record->daftar_tugas_tambahan;
                                            return empty($tugas) ? 'Tidak ada tugas tambahan.' : implode(', ', (array) $tugas);
                                        })
                                        ->columnSpanFull(),
                                ]),
                            ]),
                            
                        Tab::make('Riwayat')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('tmt_cpns_honorer')
                                        ->label('TMT CPNS / Honorer Awal')
                                        ->formatStateUsing(function ($state) {
                                            if (empty($state) || $state === '-') return '-';
                                            try { return Carbon::parse($state)->isoFormat('D MMMM Y'); } catch (\Exception $e) { return '-'; }
                                        }),
                                    TextEntry::make('tmt_pns_pppk')
                                        ->label('TMT PNS / PPPK')
                                        ->formatStateUsing(function ($state) {
                                            if (empty($state) || $state === '-') return '-';
                                            try { return Carbon::parse($state)->isoFormat('D MMMM Y'); } catch (\Exception $e) { return '-'; }
                                        }),
                                    TextEntry::make('tmt_golongan_terakhir')
                                        ->label('TMT Golongan Terakhir')
                                        ->formatStateUsing(function ($state) {
                                            if (empty($state) || $state === '-') return '-';
                                            try { return Carbon::parse($state)->isoFormat('D MMMM Y'); } catch (\Exception $e) { return '-'; }
                                        }),
                                ]),
                                TextEntry::make('kalkulasi_masa_kerja')
                                    ->label('Masa Kerja Terhitung (Otomatis)')
                                    ->getStateUsing(fn ($record) => "Masa Kerja Golongan: " . intval($record->masa_kerja_golongan) . " Tahun | Keseluruhan: " . intval($record->masa_kerja_keseluruhan) . " Tahun")
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'mt-4 font-semibold text-primary-600']),
                            ]),
                            
                        Tab::make('Pendidikan')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('pendidikan_ijazah')->label('Tingkat Ijazah')->default('-'),
                                    TextEntry::make('pendidikan_tahun')->label('Tahun Lulus')->default('-'),
                                    TextEntry::make('pendidikan_jurusan')->label('Fakultas / Jurusan')->columnSpanFull()->default('-'),
                                ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public function simpanFoto(): void
    {
        if ($this->pegawai) {
            $data = $this->fotoForm->getState();
            $this->pegawai->updateQuietly([
                'foto' => $data['foto'],
            ]);
            Notification::make()->title('Foto Profil Berhasil Diperbarui')->success()->send();
        }
    }

    public function simpanPassword(): void
    {
        $data = $this->passwordForm->getState();
        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);
        
        $this->passwordForm->fill();
        Notification::make()->title('Password Berhasil Diubah')->success()->send();
    }
}