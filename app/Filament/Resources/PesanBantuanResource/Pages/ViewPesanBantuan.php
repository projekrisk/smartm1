<?php

namespace App\Filament\Resources\PesanBantuanResource\Pages;

use App\Filament\Resources\PesanBantuanResource;
use App\Filament\Resources\SiswaResource; 
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Forms;
use App\Models\PesanBantuanDetail;
use Illuminate\Support\Facades\Auth;

class ViewPesanBantuan extends ViewRecord
{
    protected static string $resource = PesanBantuanResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        if (!$this->record->is_read_admin) {
            $this->record->update([
                'is_read_admin' => true,
                'status' => $this->record->status === 'Open' ? 'Diproses' : $this->record->status
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('lihat_profil')
                ->label('Profil Siswa')
                ->icon('heroicon-o-identification')
                ->color('info')
                ->url(fn () => SiswaResource::getUrl('edit', ['record' => $this->record->siswa_id]))
                ->openUrlInNewTab()
                ->visible(fn () => filled($this->record->siswa_id)),

            Actions\Action::make('balas')
                ->label('Balas Pesan')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('primary')
                ->form([
                    Forms\Components\Textarea::make('pesan')
                        ->label('Ketik Balasan')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    PesanBantuanDetail::create([
                        'pesan_bantuan_id' => $this->record->id,
                        'pengirim' => 'Admin',
                        'user_id' => Auth::id(),
                        'pesan' => $data['pesan'],
                    ]);
                    
                    $this->record->update([
                        'is_read_siswa' => false,
                        'status' => 'Diproses',
                    ]);
                })
                ->hidden(fn () => $this->record->status === 'Selesai'),

            Actions\Action::make('selesai')
                ->label('Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'Selesai']);
                })
                ->hidden(fn () => $this->record->status === 'Selesai'),
        ];
    }
}