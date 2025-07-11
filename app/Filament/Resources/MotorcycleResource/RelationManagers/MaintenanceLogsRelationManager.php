<?php

namespace App\Filament\Resources\MotorcycleResource\RelationManagers;

use App\Models\SparePart;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenanceLogs';
    protected static ?string $modelLabel = 'Servis';
    protected static ?string $pluralModelLabel = 'Servis Qeydiyyatı';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('maintenance_rule_id')
                    ->relationship('maintenanceRule', 'kural_adi')
                    ->label('Baxım Səbəbi'),
                Forms\Components\DatePicker::make('tarih')
                    ->label('Tarix')
                    ->required()->default(now()),
                Forms\Components\TextInput::make('bakim_km')
                    ->label('Baxım Anındakı KM')
                    ->numeric()->required()
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->mevcut_km),
                Forms\Components\Textarea::make('aciklama')
                    ->label(' görülən işlər haqqında qeydlər')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('kullanilan_parcalar')
                    ->label('İstifadə Olunan Ehtiyat Hissələri')
                    ->schema([
                        Forms\Components\Select::make('part_id')
                            ->label('Ehtiyat Hissəsi')
                            ->options(SparePart::all()->pluck('parca_adi', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Ədəd')
                            ->numeric()->required()->default(1),
                        Forms\Components\TextInput::make('price')
                            ->label('Qiymət (1 ədəd)')
                            ->numeric()->prefix('₼')->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('iscilik_ucreti')
                    ->label('Servis Xidmət Haqqı')
                    ->numeric()->prefix('₼')->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tarih')
            ->columns([
                Tables\Columns\TextColumn::make('tarih')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('maintenanceRule.kural_adi')->label('Baxım Səbəbi'),
                Tables\Columns\TextColumn::make('toplam_maliyet')->money('AZN'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $total = $data['iscilik_ucreti'] ?? 0;
                        if (isset($data['kullanilan_parcalar'])) {
                            foreach ($data['kullanilan_parcalar'] as $part) {
                                $total += $part['quantity'] * $part['price'];
                            }
                        }
                        $data['toplam_maliyet'] = $total;
                        return $data;
                    })
                    ->after(function (Model $record) {
                        if ($record->kullanilan_parcalar) {
                            foreach ($record->kullanilan_parcalar as $part) {
                                $sparePart = SparePart::find($part['part_id']);
                                if ($sparePart) {
                                    $sparePart->decrement('stok_adedi', $part['quantity']);
                                }
                            }
                        }
                    }),
            ]);
    }
}
