<?php

namespace App\Filament\Pages;

use App\Models\MaintenanceRule;
use App\Models\Motorcycle;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class TopluKmGuncelleme extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'Toplu KM Güncelleme';
    protected static ?string $navigationGroup = 'Servis';

    protected static string $view = 'filament.pages.toplu-km-guncelleme';

    protected ?string $heading = 'Toplu Kilometre Güncelleme';
    protected ?string $subheading = 'Burada sadece durumu "Kirada" olan motosikletlerin kilometrelerini güncelleyebilirsiniz.';

    public function table(Table $table): Table
    {
        return $table
            ->query(Motorcycle::query()->where('status', 'kirada'))
            ->columns([
                Tables\Columns\TextColumn::make('plaka')->label('Plaka'),
                Tables\Columns\TextColumn::make('marka'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('mevcut_km')->label('Mevcut KM'),
            ])
            ->actions([
                Tables\Actions\Action::make('update_km')
                    ->label('KM Güncelle')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Forms\Components\TextInput::make('yeni_km')
                            ->label('Yeni Kilometre')
                            ->numeric()
                            ->required()
                            ->placeholder(fn (Motorcycle $record): string => "Mevcut: {$record->mevcut_km}"),
                        Forms\Components\CheckboxList::make('maintenance_rule_ids')
                            ->label('Eş zamanlı olarak bu bakım kurallarını başlat')
                            ->options(MaintenanceRule::all()->pluck('name', 'id'))
                            ->columns(2),
                    ])
                    ->action(function (Motorcycle $record, array $data, Collection $get) {
                        $yeniKm = $data['yeni_km'];
                        $ruleIds = $data['maintenance_rule_ids'];

                        // 1. KM'yi ve log'u güncelle
                        $record->update(['mevcut_km' => $yeniKm]);
                        $record->mileageLogs()->create(['km' => $yeniKm]);

                        // 2. Seçilen bakım kuralları için takip kaydı oluştur
                        if (!empty($ruleIds)) {
                            $rules = MaintenanceRule::find($ruleIds);
                            foreach ($rules as $rule) {
                                $record->maintenanceTrackings()->create([
                                    'maintenance_rule_id' => $rule->id,
                                    'start_km' => $yeniKm,
                                    'next_service_km' => $yeniKm + $rule->km_interval,
                                ]);
                            }
                        }

                        Notification::make()
                            ->title('Kilometre başarıyla güncellendi.')
                            ->success()
                            ->send();
                    })
            ]);
    }

    protected function getFormModel(): string
    {
        return Motorcycle::class;
    }
} 