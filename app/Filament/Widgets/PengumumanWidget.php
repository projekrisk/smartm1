<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Models\Pengumuman;

class PengumumanWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.widgets.pengumuman-widget';
    
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];
    
    protected static ?int $sort = -2; 

    public ?Pengumuman $pengumumanTerbaru = null;

    public function mount(): void
    {
        $this->pengumumanTerbaru = Pengumuman::with('pembuat')
            ->where('is_aktif', true)
            ->latest()
            ->first();
    }

    public function bacaSelengkapnyaAction(): Action
    {
        return Action::make('bacaSelengkapnya')
            ->label('Baca')
            ->icon('heroicon-m-book-open')
            ->color('primary')
            ->modalHeading(fn () => $this->pengumumanTerbaru?->judul ?? 'Pengumuman')
            ->modalContent(fn () => view('filament.widgets.pengumuman-modal', [
                'isi' => $this->pengumumanTerbaru?->isi,
                'tanggal' => $this->pengumumanTerbaru?->created_at->isoFormat('D MMMM Y, H:mm'),
                'penulis' => $this->pengumumanTerbaru?->pembuat->name ?? 'Admin',
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup');
    }
}