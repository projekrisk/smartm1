<?php

namespace App\Filament\Resources\JurnalGuruResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KehadiranRelationManager extends RelationManager
{
    protected static string $relationship = 'kehadiranPelajaran';
    protected static ?string $title = 'Daftar Hadir Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Ubah Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Sakit' => 'Sakit',
                        'Izin' => 'Izin',
                        'Alpa' => 'Alpa',
                        'Terlambat' => 'Terlambat',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->placeholder('Contoh: Surat di meja piket'),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query
                ->join('siswas', 'kehadiran_pelajarans.siswa_id', '=', 'siswas.id')
                ->orderBy('siswas.nama_lengkap', 'asc')
                ->select('kehadiran_pelajarans.*')
            )
            
            ->recordTitleAttribute('id')
            ->recordAction('edit')
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit' => 'warning',
                        'Izin' => 'info',
                        'Alpa' => 'danger',
                        'Terlambat' => 'purple',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->default('-'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn ($record) => 'Kehadiran: ' . ($record->siswa->nama_lengkap ?? '-'))
                    ->modalWidth('md'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('ubah_status_massal')
                        ->label('Ubah Status Massal')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(['Hadir'=>'Hadir', 'Sakit'=>'Sakit', 'Izin'=>'Izin', 'Alpa'=>'Alpa', 'Terlambat'=>'Terlambat'])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            foreach ($records as $record) { $record->update(['status' => $data['status']]); }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->paginated(false);
    }
}