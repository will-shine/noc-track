<?php

namespace App\Filament\Resources\Troubleshoots\Widgets;

use App\Filament\Resources\Troubleshoots\Pages\ListTroubleshoots;
use App\Models\Troubleshoot;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\HtmlString; // <--- PASTIKAN BARIS INI ADA
use Illuminate\Support\Str;

class TroubleStat extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    // Tambahkan baris ini untuk mengatur jumlah kolom
    // Kita set 6 agar semua stat (1 total + 5 tipe) berada dalam satu baris
    // protected int | array | null $columns = 6;

    // Ganti angka 6 dengan array responsif
    protected int | array | null $columns = [
        'default' => 2, // Di HP tampil 2 kolom (jadi 3 baris)
        'sm' => 3,      // Di Tablet kecil tampil 3 kolom (jadi 2 baris)
        'lg' => 6,      // Di Monitor besar tampil 6 kolom (jadi 1 baris)
    ];

    protected function getTablePage(): string
    {
        return ListTroubleshoots::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        // Mapping warna manual
        $colors = [
            'psb' => '#11863c',       // Success (Hijau)
            'dismantle' => '#ef4444', // Danger (Merah)
            'maintenance' => '#f59e0b', // Warning (Kuning)
            'service' => '#1757bd',    // Info (Biru)
            'accident' => '#fc0b1f',   // Purple
        ];

        $stats = [];

        // Contoh untuk Total (Primary)
        $total = (clone $query)->count();
        $stats[] = Stat::make('Total Troubleshoot', new HtmlString('
        <span style="color: #0003b6; font-weight: bold; font-size: 1.5rem;">' . number_format($total, 0) . '</span>
    '));

        $types = ['psb', 'dismantle', 'maintenance', 'service', 'accident'];

        foreach ($types as $type) {
            $count = (clone $query)->where('type', $type)->count();
            $hex = $colors[$type] ?? '#6b7280';

            $stats[] = Stat::make(Str::headline($type), new HtmlString('
            <span style="color: ' . $hex . '; font-weight: bold; font-size: 1.5rem;">' . number_format($count, 0) . '</span>
        '));
        }

        return $stats;
    }
}
