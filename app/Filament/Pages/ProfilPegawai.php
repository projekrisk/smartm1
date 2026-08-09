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
use Filament\Infolists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Split;

class ProfilPegawai extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    protected static ?string $title = 'Profil Saya';
    protected static string $view = 'filament.pages.profil-pegawai';
    
    // Menyembunyikan dari Sidebar kiri, karena akan kita taruh di Menu Kanan Atas
    protected static bool $shouldRegisterNavigation = false;

    public ?array $passwordData = [];
    public ?array $fotoData = [];
    
    public ?Pegawai $pegawai = null;

    public function mount(): void
    {
        // Cari data pegawai yang terhubung dengan user yang sedang login
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
                    // TRIK KHUSUS: Memaksa Filament untuk meratakan kolom ini ke tengah
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
                Section::make('Identitas Diri')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('nama_lengkap')
                                ->label('Nama Lengkap & Gelar')
                                ->weight('bold'),
                            TextEntry::make('nik')
                                ->label('NIK (No. KTP)')
                                ->copyable()
                                ->default('-'),
                            TextEntry::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->default('-'),
                            TextEntry::make('tempat_lahir')
                                ->label('Tempat, Tanggal Lahir')
                                ->formatStateUsing(fn ($record) => ($record->tempat_lahir ?? '-') . ', ' . ($record->tanggal_lahir ? \Carbon\Carbon::parse($record->tanggal_lahir)->translatedFormat('d F Y') : '-')),
                            TextEntry::make('agama')
                                ->label('Agama')
                                ->default('-'),
                        ]),
                    ]),

                Section::make('Informasi Kepegawaian & Akademik')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('nip')
                                ->label('NIP / NUPTK')
                                ->copyable()
                                ->default('-'),
                            TextEntry::make('jenis_ptk')
                                ->label('Jenis PTK')
                                ->badge()
                                ->color('info')
                                ->default('-'),
                            TextEntry::make('jabatan')
                                ->label('Jabatan')
                                ->default('-'),
                            TextEntry::make('status_pegawai')
                                ->label('Status Pegawai')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Aktif' => 'success',
                                    'Pensiun' => 'warning',
                                    'Pindah/Mutasi' => 'danger',
                                    default => 'primary',
                                })
                                ->default('Aktif'),
                            TextEntry::make('tmt_kerja')
                                ->label('Terhitung Mulai Tanggal (TMT)')
                                ->date('d F Y')
                                ->default('-'),
                            TextEntry::make('pendidikan_terakhir')
                                ->label('Pendidikan Terakhir')
                                ->formatStateUsing(fn ($record) => ($record->pendidikan_terakhir ?? '-') . ' - ' . ($record->jurusan ?? '-')),
                        ]),
                    ]),

                Section::make('Kontak & Alamat')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('telepon')
                                ->label('Nomor WhatsApp / Telepon')
                                ->icon('heroicon-m-phone')
                                ->default('-'),
                            TextEntry::make('email')
                                ->label('Alamat Email')
                                ->icon('heroicon-m-envelope')
                                ->default('-'),
                            TextEntry::make('alamat')
                                ->label('Alamat Lengkap Rumah')
                                ->columnSpanFull()
                                ->default('-'),
                        ]),
                    ]),
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
        
        // Kosongkan kembali form password setelah sukses
        $this->passwordForm->fill();
        Notification::make()->title('Password Berhasil Diubah')->success()->send();
    }
}