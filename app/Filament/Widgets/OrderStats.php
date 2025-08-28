<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getCards(): array
    {
        return [
            Card::make('Total Pesanan', Order::count())
                ->icon('heroicon-m-shopping-cart')
                ->color('primary'),

            Card::make('Pesanan Selesai', Order::where('status', 'completed')->count())
                ->icon('heroicon-m-check-circle')
                ->color('success'),

            Card::make('Pendapatan Total', 'Rp ' . number_format(Order::sum('total'), 0, ',', '.'))
                ->icon('heroicon-m-currency-dollar')
                ->color('danger'), // merah
        ];
    }
}
