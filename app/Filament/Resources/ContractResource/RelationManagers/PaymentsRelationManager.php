<?php

namespace App\Filament\Resources\ContractResource\RelationManagers;

use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $modelLabel = 'Ödəmə';
    protected static ?string $pluralModelLabel = 'Ödəmə Planı';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('vade_tarihi')
                    ->label('Vadə Tarixi')
                    ->required(),
                Forms\Components\TextInput::make('tutar')
                    ->label('Məbləğ')
                    ->numeric()
                    ->prefix('₼')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'odenmedi' => 'Ödənməyib',
                        'odendi' => 'Ödənib',
                        'gecikti' => 'Gecikir',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('vade_tarihi')
            ->columns([
                Tables\Columns\TextColumn::make('vade_tarihi')
                    ->label('Vadə Tarixi')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tutar')
                    ->label('Məbləğ')
                    ->money('AZN'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'odenmedi' => 'warning',
                        'odendi' => 'success',
                        'gecikti' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('tahsilatYap')
                    ->label('Ödəniş Et')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('tutar')
                            ->label('Alınan Məbləğ')
                            ->numeric()
                            ->prefix('₼')
                            ->required()
                            ->default(fn (Model $record) => $record->tutar),
                        Forms\Components\Select::make('odeme_yontemi')
                            ->label('Ödəmə Yöntəmi')
                            ->options([
                                'nağd' => 'Nağd',
                                'kart' => 'Kart',
                                'havale' => 'Köçürmə',
                            ])
                            ->required(),
                        Forms\Components\DatePicker::make('odeme_tarihi')
                            ->label('Ödəmə Tarixi')
                            ->required()
                            ->default(now()),
                        Forms\Components\Textarea::make('notlar')
                            ->label('Qeydlər'),
                    ])
                    ->action(function (array $data, Model $record): void {
                        Transaction::create([
                            'contract_id' => $record->contract_id,
                            'courier_id' => $record->contract->courier_id,
                            'payment_id' => $record->id,
                            'tutar' => $data['tutar'],
                            'odeme_tarihi' => $data['odeme_tarihi'],
                            'odeme_yontemi' => $data['odeme_yontemi'],
                            'notlar' => $data['notlar'],
                        ]);

                        $record->status = 'odendi';
                        $record->save();
                        
                        Notification::make()
                            ->title('Ödəniş uğurla qeydə alındı')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Model $record): bool => $record->status !== 'odendi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
