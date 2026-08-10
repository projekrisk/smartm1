<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeteranganAktifResource\Pages;
use App\Models\SuratKeteranganAktif;
use App\Models\Siswa;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SuratKeteranganAktifResource extends Resource
{
    protected static ?string $model = SuratKeteranganAktif::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';    
    protected static ?string $slug = 'surat-keterangan-aktif';
    protected static ?string $navigationLabel = 'Keterangan Aktif';
    protected static ?string $pluralModelLabel = 'Surat Keterangan Aktif';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\DatePicker::make('tanggal_surat')
                                ->label('Tanggal Surat')
                                ->default(now())
                                ->required(),

                            Forms\Components\TextInput::make('nomor_urut')
                                ->label('Nomor Surat')
                                ->numeric()
                                ->placeholder('Contoh: 123')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $urut = $get('nomor_urut');
                                    if ($urut) {
                                        $tahun = date('Y');
                                        $set('nomor_surat', "421.3/{$urut}/SMA.01-MLP/{$tahun}");
                                    }
                                })
                                ->dehydrated(false),

                            Forms\Components\TextInput::make('nomor_surat')
                                ->label('Format Lengkap (Otomatis)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->disabled()
                                ->dehydrated(),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('siswa_id')
                                ->label('Nama Siswa')
                                ->relationship('siswa', 'nama_lengkap', fn (Builder $query) => $query->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa')->with('kelas'))
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_lengkap} (Kelas {$record->kelas?->nama_kelas})")
                                ->searchable(['nama_lengkap', 'nis', 'nisn'])
                                ->preload()
                                ->required(),

                            Forms\Components\TextInput::make('tahun_ajaran')
                                ->label('Tahun Ajaran')
                                ->default(date('Y') . '/' . (date('Y') + 1))
                                ->required(),

                            Forms\Components\Select::make('penandatangan_id')
                                ->label('Penandatangan (Kepala Sekolah)')
                                ->relationship('penandatangan', 'nama', fn (Builder $query) => $query->where('jenis_ptk', 'like', '%Kepala Sekolah%'))
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        Forms\Components\Hidden::make('dibuat_oleh')->default(fn () => Auth::id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')->label('Nomor Surat')->searchable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')->label('Siswa')->searchable(),
                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')->label('Kelas')->badge()->color('info'),
                Tables\Columns\TextColumn::make('tanggal_surat')->label('Tanggal')->date('d M Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.keterangan-aktif', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratKeteranganAktifs::route('/'),
            'create' => Pages\CreateSuratKeteranganAktif::route('/create'),
            'edit' => Pages\EditSuratKeteranganAktif::route('/{record}/edit'),
        ];
    }
}