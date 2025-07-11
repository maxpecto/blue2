<?php

namespace App\Filament\Widgets;

use App\Models\MotorcycleMaintenanceTracking;
use Closure;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingServicesWidget extends BaseWidget
{
    protected static ?string $heading = 'Yaklaşan ve Gecikmiş Servisler';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MotorcycleMaintenanceTracking::query()
                    ->from('motorcycle_maintenance_trackings as mmt')
                    ->join('motorcycles as m', 'mmt.motorcycle_id', '=', 'm.id')
                    ->join('maintenance_rules as mr', 'mmt.maintenance_rule_id', '=', 'mr.id')
                    ->where('mmt.is_completed', false)
                    ->whereRaw('m.mevcut_km >= (mmt.next_service_km - mr.warning_km)')
                    ->select('mmt.*') // Ensure we get all columns from the tracking table
            )
            ->columns([
                Tables\Columns\TextColumn::make('motorcycle.plaka')->label('Plaka'),
                Tables\Columns\TextColumn::make('maintenanceRule.name')->label('Bakım'),
                Tables\Columns\TextColumn::make('motorcycle.mevcut_km')->label('Mevcut KM'),
                Tables\Columns\TextColumn::make('next_service_km')->label('Servis KM'),
                Tables\Columns\TextColumn::make('kalan_km')
                    ->label('Durum')
                    ->formatStateUsing(function ($record) {
                        $kalan = $record->next_service_km - $record->motorcycle->mevcut_km;
                        if ($kalan <= 0) {
                            return "Servis " . abs($kalan) . " KM gecikti";
                        }
                        return "Servise " . $kalan . " KM kaldı";
                    })
                    ->color(fn ($record) => ($record->next_service_km - $record->motorcycle->mevcut_km) <= 0 ? 'danger' : 'warning'),
            ])
            ->defaultSort('next_service_km', 'asc')
            ->actions([
                Tables\Actions\Action::make('servis_yapildi')
                    ->label('Servis Yapıldı')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (MotorcycleMaintenanceTracking $record) {
                        $record->update(['is_completed' => true]);
                    }),
            ]);
    }
}
