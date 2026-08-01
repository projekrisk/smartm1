<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Pegawai;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class DirektoriGuru extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Direktori Guru';
    protected static string $view = 'filament.siswa.pages.direktori-guru';
    protected static ?string $slug = 'direktori-guru';
    
    // Sembunyikan dari navigasi standar karena kita pakai tombol di Dasbor Android
    protected static bool $shouldRegisterNavigation = false; 

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    public function table(Table $table): Table
    {
        return $table
            ->query(Pegawai::query())
            ->columns([
                // Mengubah tampilan tabel standar menjadi bentuk List (Cocok untuk HP)
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('foto')
                        ->circular()
                        ->defaultImageUrl(asset('images/default-avatar.png'))
                        ->getStateUsing(fn ($record) => $record->foto && \Illuminate\Support\Facades\Storage::disk('publik_upload')->exists($record->foto) ? \Illuminate\Support\Facades\Storage::disk('publik_upload')->url($record->foto) : null)
                        ->grow(false),
                        
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('nama')
                            ->weight('bold')
                            ->searchable()
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium),
                            
                        Tables\Columns\TextColumn::make('jenis_ptk')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Kepala Sekolah' => 'danger',
                                'Guru' => 'info',
                                'Tenaga Kependidikan' => 'success',
                                default => 'gray',
                            }),
                            
                        Tables\Columns\TextColumn::make('tugas_utama')
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Small)
                            ->color('gray'),
                    ])->space(1),
                ])
            ])
            ->filters([
                // Filter Tab diganti dengan Dropdown agar muat di layar HP
                Tables\Filters\SelectFilter::make('jenis_ptk')
                    ->label('Filter Kategori')
                    ->options([
                        'Kepala Sekolah' => 'Kepala Sekolah',
                        'Guru' => 'Guru / Pendidik',
                        'Tenaga Kependidikan' => 'Staf / Tata Usaha',
                    ])
                    ->native(false)
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->paginated([10, 25, 50])
            ->actions([]) // Mode Read-Only
            ->bulkActions([]); // Mode Read-Only
    }
}