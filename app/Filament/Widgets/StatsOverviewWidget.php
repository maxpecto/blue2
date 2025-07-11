<?php

namespace App\Filament\Widgets;

use App\Models\Courier;
use App\Models\Investor;
use App\Models\Motorcycle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Toplam Yatırımcı', Investor::count())
                ->description('Sistemdeki toplam yatırımcı sayısı')
                ->icon('heroicon-o-user-group'),
            Stat::make('Aktif Kurye', Courier::where('status', 'aktif')->count())
                ->description('Mevcut aktif kurye sayısı')
                ->icon('heroicon-o-identification'),
            Stat::make('Anbardakı Motosiklet', Motorcycle::where('status', 'depoda')->count())
                ->description('Hazırda olan motosiklet sayısı')
                ->color('success')
                ->icon('heroicon-o-truck'),
            Stat::make('İcarədəki Motosiklet', Motorcycle::where('status', 'kirada')->count())
                ->description('Hal-hazırda icarədə olan motosiklet sayı')
                ->color('warning')
                ->icon('heroicon-o-lock-closed'),
        ];
    }
}
