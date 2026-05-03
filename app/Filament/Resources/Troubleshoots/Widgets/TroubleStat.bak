<?php

namespace App\Filament\Resources\Troubleshoots\Widgets;

use App\Filament\Resources\Troubleshoots\Pages\ListTroubleshoots;
use App\Models\Troubleshoot;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

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
        $trendMonth = Trend::model(Troubleshoot::class)
            ->between(
                start: now()->subMonth(),
                end: now(),
            )
            ->dateColumn('incident_time')
            ->perDay()
            ->count();

        // $trendYear = Trend::model(Troubleshoot::class)
        //     ->between(
        //         start: now()->subYear(),
        //         end: now(),
        //     )
        //     ->dateColumn('incident_time')
        //     ->perMonth()
        //     ->count();

        // $trendWeek = Trend::model(Troubleshoot::class)
        //     ->between(
        //         start: now()->subYear(),
        //         end: now(),
        //     )
        //     ->dateColumn('incident_time')
        //     ->perWeek()
        //     ->count();


        // // 1. Hitung Total Revenue
        // $totalRevenue = $this->getPageTableQuery()
        //     ->whereHas('category', fn($query) => $query->where('type', 'revenue'))
        //     ->sum('amount');

        // // 2. Hitung Total Expense
        // $totalExpense = $this->getPageTableQuery()
        //     ->whereHas('category', fn($query) => $query->where('type', 'expense'))
        //     ->sum('amount');

        // // 3. Hitung Selisih (Net Profit/Loss)
        // $netCashFlow = $totalRevenue - $totalExpense;

        return [


            Stat::make('CashFlows Count', number_format($this->getPageTableQuery()->count(), 0))
                ->chart(
                    $trendMonth
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->color('gray')
                ->description('-'),


            // Stat::make('Average Amount', 'Rp' . number_format((float) $this->getPageTableQuery()->avg('amount'), 0, ',', '.'))
            //     ->chart(
            //         $trendYear
            //             ->map(fn(TrendValue $value) => $value->aggregate)
            //             ->toArray()
            //     )
            //     ->color('success')
            //     ->description('-'),

            // Stat::make('Total CashFlow', 'Rp' . number_format((float) $this->getPageTableQuery()->sum('amount'), 0, ',', '.'))
            //     ->chart(
            //         $trendWeek
            //             ->map(fn(TrendValue $value) => $value->aggregate)
            //             ->toArray()
            //     )
            //     ->color('info')
            //     ->description('-'),

            // // Card baru untuk Net Profit / Selisih
            // Stat::make('CashFlows', 'Rp' . number_format($netCashFlow, 0, ',', '.'))
            //     ->description($netCashFlow >= 0 ? 'Surplus' : 'Defisit')
            //     ->descriptionIcon($netCashFlow >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            //     ->color($netCashFlow >= 0 ? 'success' : 'danger'),

        ];
    }
}
