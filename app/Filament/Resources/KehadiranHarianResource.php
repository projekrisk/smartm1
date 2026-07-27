<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KehadiranHarianResource\Pages;
use App\Models\RekapKehadiran;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KehadiranHarianResource extends Resource
{
    protected static ?string $model = RekapKehadiran::class;

    // 1. IZINKAN GURU UNTUK MASUK KE MENU INI
    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf', 'guru']);
    }

    // 2. KUNCI AKSES! GURU TIDAK BOLEH MENAMBAH
    public static function canCreate(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    // 3. KUNCI AKSES! GURU TIDAK BOLEH MENGEDIT (HANYA BISA LIHAT)
    public static function canEdit(Model $record): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    // 4. KUNCI AKSES! GURU TIDAK BOLEH MENGHAPUS
    public static function canDelete(Model $record): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    protected static ?string $navigationGroup = 'Kehadiran Siswa';
    protected static ?string $slug = 'kehadiran-harian';
    protected static ?string $navigationIcon = 'heroicon-o-document-check';  
    protected static ?string $navigationLabel = 'Absensi Harian';
    protected static ?string $pluralModelLabel = 'Rekap Absensi Harian';
    protected static ?string $modelLabel = 'Absensi Harian';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Kelas & Tanggal')
                    ->description('Silakan pilih tanggal dan kelas. Daftar siswa akan otomatis di-generate setelah Anda menekan tombol "Create" di bawah.')
                    ->schema([
                        Forms\Components\Hidden::make('tahun_ajaran_id')
                            ->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id),

                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Absensi')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('kelas_id')
                            ->label('Pilih Kelas')
                            ->options(\App\Models\Kelas::pluck('nama_kelas', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (string $operation) => $operation !== 'create')
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule, \Filament\Forms\Get $get) {
                                    return $rule->where('tanggal', $get('tanggal'));
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Absensi untuk kelas ini pada tanggal tersebut sudah pernah dibuat. Silakan cari di tabel untuk mengeditnya.',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Validasi Data')
                    ->schema([
                        Forms\Components\Toggle::make('is_valid')
                            ->label('Validasi Selesai (Dikunci)')
                            ->helperText('Nyalakan jika absensi kelas ini sudah Anda periksa dan sah.')
                            ->default(false),
                        Forms\Components\Hidden::make('divalidasi_oleh')
                            ->default(fn () => Auth::id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query
                ->orderByRaw('(SELECT LENGTH(nama_kelas) FROM kelas WHERE kelas.id = rekap_kehadiran.kelas_id) ASC')
                ->orderByRaw('(SELECT nama_kelas FROM kelas WHERE kelas.id = rekap_kehadiran.kelas_id) ASC')
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('is_valid')
                    ->label('Tervalidasi')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('validator.name')
                    ->label('Staf Validasi')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas'),
                    
                Tables\Filters\Filter::make('filter_waktu')
                    ->form([
                        Forms\Components\Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ])
                            ->default(now()->format('m')), 
                        Forms\Components\Select::make('tahun')
                            ->label('Tahun')
                            ->options(function () {
                                $years = [];
                                $currentYear = now()->year;
                                for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->default(now()->year),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['bulan'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $month): \Illuminate\Database\Eloquent\Builder => $query->whereMonth('tanggal', $month),
                            )
                            ->when(
                                $data['tahun'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $year): \Illuminate\Database\Eloquent\Builder => $query->whereYear('tanggal', $year),
                            );
                    }),
            ])
            ->actions([
                // JIKA GURU, AKAN MUNCUL TOMBOL VIEW INI
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),
                
                // JIKA ADMIN, AKAN MUNCUL TOMBOL EDIT INI
                Tables\Actions\EditAction::make()->label('Buka Daftar Hadir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array 
    { 
        return [
            \App\Filament\Resources\KehadiranHarianResource\RelationManagers\DaftarHadirRelationManager::class,
        ]; 
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKehadiranHarians::route('/'),
            'create' => Pages\CreateKehadiranHarian::route('/create'),
            'view' => Pages\ViewKehadiranHarian::route('/{record}'), // Menambah route view
            'edit' => Pages\EditKehadiranHarian::route('/{record}/edit'),
        ];
    }
}