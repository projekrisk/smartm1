<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenResource\Pages;
use App\Models\Dokumen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;

class DokumenResource extends Resource
{
    protected static ?string $model = Dokumen::class;
    protected static ?string $slug = 'dokumen-arsip';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Dokumen & Arsip';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Dokumen';
    
    public static function canViewAny(): bool { return true; }
    public static function canCreate(): bool { return Auth::user()->peran === 'admin'; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return Auth::user()->peran === 'admin'; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return Auth::user()->peran === 'admin'; }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (in_array(Auth::user()->peran, ['guru', 'staf'])) {
            $query->whereIn('target_audience', ['Semua', 'Guru & Staf']);
        }
        return $query->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Dokumen')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('keterangan')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('target_audience')
                            ->label('Target Pembaca Dokumen')
                            ->options([
                                'Semua' => 'Semua (Siswa, Guru & Staf)',
                                'Guru & Staf' => 'Hanya Guru & Staf',
                                'Siswa' => 'Hanya Siswa',
                            ])
                            ->default('Semua')
                            ->required(),
                        Forms\Components\Radio::make('jenis_sumber')
                            ->label('Sumber Dokumen')
                            ->options([
                                'File' => 'Upload File Langsung',
                                'Link' => 'Tautan / Link Eksternal (Google Drive, dll)',
                            ])
                            ->default('File')
                            ->live()
                            ->required(),
                            
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload File Dokumen')
                            ->disk('publik_upload')
                            ->directory('dokumen_publik')
                            ->openable()
                            ->downloadable()
                            ->maxSize(5120)
                            ->visible(fn (Get $get) => $get('jenis_sumber') === 'File')
                            ->required(fn (Get $get) => $get('jenis_sumber') === 'File')
                            ->columnSpanFull(),
                            
                        Forms\Components\TextInput::make('url_link')
                            ->label('Tautan / URL')
                            ->url()
                            ->placeholder('https://drive.google.com/...')
                            ->visible(fn (Get $get) => $get('jenis_sumber') === 'Link')
                            ->required(fn (Get $get) => $get('jenis_sumber') === 'Link')
                            ->columnSpanFull(),
                            
                        Forms\Components\Hidden::make('dibuat_oleh')->default(fn () => Auth::id()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('target_audience')
                    ->label('Target')
                    ->badge()
                    ->color(fn ($state) => match($state) { 'Semua'=>'success', 'Siswa'=>'info', 'Guru & Staf'=>'warning' }),
                Tables\Columns\TextColumn::make('jenis_sumber')
                    ->label('Tipe')
                    ->icon(fn ($state) => $state === 'File' ? 'heroicon-o-document-arrow-down' : 'heroicon-o-link')
                    ->badge()->color('gray'),
                Tables\Columns\TextColumn::make('created_at')->label('Diterbitkan')->date('d M Y')->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('buka_dokumen')
                    ->label(fn ($record) => $record->jenis_sumber === 'File' ? 'Unduh File' : 'Buka Tautan')
                    ->icon(fn ($record) => $record->jenis_sumber === 'File' ? 'heroicon-o-arrow-down-tray' : 'heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn ($record) => $record->jenis_sumber === 'File' ? url('/uploads/' . $record->file_path) : $record->url_link)
                    ->openUrlInNewTab(),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumens::route('/'),
            'create' => Pages\CreateDokumen::route('/create'),
            'edit' => Pages\EditDokumen::route('/{record}/edit'),
        ];
    }
}