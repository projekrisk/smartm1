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
    // Wajib dipanggil agar tombol Modal bisa berfungsi di dalam Custom Widget
    use InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.widgets.pengumuman-widget';
    
    // PERBAIKAN: Memaksa widget ini agar membelah layar (split) pada mode Desktop/Tablet
    // md = Monitor/Tablet, xl = Layar Lebar
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];
    
    // PERBAIKAN: Diubah menjadi -2 agar berada di atas widget Jadwal (yang memakan layar penuh)
    // dan bersebelahan persis dengan Widget Welcome (yang default-nya -3)
    protected static ?int $sort = -2; 

    public ?Pengumuman $pengumumanTerbaru = null;

    public function mount(): void
    {
        // Menarik 1 pengumuman terbaru yang statusnya aktif
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
            // Memanggil file blade khusus untuk desain pop-up
            ->modalContent(fn () => view('filament.widgets.pengumuman-modal', [
                'isi' => $this->pengumumanTerbaru?->isi,
                'tanggal' => $this->pengumumanTerbaru?->created_at->isoFormat('D MMMM Y, H:mm'),
                'penulis' => $this->pengumumanTerbaru?->pembuat->name ?? 'Admin',
            ]))
            ->modalSubmitAction(false) // Sembunyikan tombol Submit
            ->modalCancelActionLabel('Tutup');
    }
}