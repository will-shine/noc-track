<?php

namespace App\Filament\Resources\Troubleshoots\Widgets;

use App\Filament\Resources\Troubleshoots\Pages\ListTroubleshoots;
use App\Models\Troubleshoot;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Str;

class TroubleStat extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;


    protected function getTablePage(): string
    {
        return ListTroubleshoots::class;
    }


    protected function getStats(): array
    {
        // 1. Ambil query tabel saat ini (sudah mempertimbangkan filter, tanggal, dll.)
        $query = $this->getPageTableQuery();

        // 2. Hitung jumlah total data
        $total = (clone $query)->count();

        // 3. Kelompokkan berdasarkan tipe untuk membuat statistik dinamis
        $stats = [
            Stat::make('Total Troubleshoot', number_format($total, 0))
                ->color('primary')
            // ->description('Total keseluruhan data'),
        ];

        // Lakukan query untuk menghitung total masing-masing tipe berdasarkan data yang terfilter
        $types = ['psb', 'dismantle', 'maintenance', 'service', 'incident'];

        foreach ($types as $type) {
            $count = (clone $query)->where('type', $type)->count();

            // Tambahkan Stat untuk setiap tipe
            $stats[] = Stat::make(Str::headline($type), number_format($count, 0))
                ->color($this->getColorForType($type));
            // ->description('Total ' . Str::headline($type));
        }

        return $stats;
    }

    // Fungsi pembantu untuk menentukan warna card berdasarkan tipe
    protected function getColorForType(string $type): string
    {
        return match ($type) {
            'psb' => 'success',
            'dismantle' => 'danger',
            'maintenance' => 'warning',
            'service' => 'info',
            default => 'gray',
        };
    }
}
