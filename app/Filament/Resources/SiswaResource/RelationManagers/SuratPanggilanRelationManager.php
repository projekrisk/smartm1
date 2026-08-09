<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SuratPanggilanRelationManager extends RelationManager
{
    protected static string $relationship = 'suratPanggilan';
    protected static ?string $title = 'Panggilan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                Forms\Components\TextInput::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Dibuat' => 'Baru Dibuat',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ])
                    ->default('Dibuat')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_surat')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_panggilan')
                    ->label('Tgl. Pertemuan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_panggilan')
                    ->label('Waktu')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('tempat_pertemuan')
                    ->label('Tempat'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dibuat' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'primary',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah Status'),
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.panggilan', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => in_array(Auth::user()->peran, ['admin'])),
                ]),
            ]);
    }
}