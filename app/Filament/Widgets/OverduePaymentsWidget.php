<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Filament\Tables\Columns\TextColumn;

class OverduePaymentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Gecikmiş Ödemeler';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()->where('status', 'odenmemis')->where('payment_date', '<', Carbon::today())
            )
            ->columns([
                TextColumn::make('contract.id')
                    ->label('Müqavilə No'),
                TextColumn::make('contract.courier.full_name')
                    ->label('Kurye'),
                TextColumn::make('payment_date')
                    ->label('Ödəmə Tarixi')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Məbləğ')
                    ->money('AZN'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn (string $state): string => __("payment.status.{$state}")),
            ])
            ->paginated(false);
    }
}
