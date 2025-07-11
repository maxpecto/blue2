<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingPaymentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Yaklaşan Ödemeler (30 gün)';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->where('status', 'odenmemis')
                    ->whereBetween('payment_date', [Carbon::now(), Carbon::now()->addDays(30)])
                    ->orderBy('payment_date', 'asc')
            )
            ->columns([
                TextColumn::make('contract.courier.full_name')
                    ->label('Kurye')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Məbləğ')
                    ->money('AZN')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Ödəmə Tarixi')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'odendi' => 'success',
                        'odenmemis' => 'warning',
                        'gecikdi' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(function (string $state) {
                        return __("payment.status.{$state}");
                    }),
            ]);
    }
}
