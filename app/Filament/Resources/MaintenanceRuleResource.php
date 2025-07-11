<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceRuleResource\Pages;
use App\Models\MaintenanceRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaintenanceRuleResource extends Resource
{
    protected static ?string $model = MaintenanceRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $modelLabel = 'Baxım Qaydası';
    protected static ?string $pluralModelLabel = 'Baxım Qaydaları';
    protected static ?string $navigationGroup = 'Servis';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Qayda Adı (Məs: Yağ dəyişimi)')
                    ->required(),
                Forms\Components\TextInput::make('km_interval')
                    ->label('Periyod (KM)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('warning_km')
                    ->label('Xəbərdarlıq (KM)')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Açıqlama')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Qayda Adı'),
                Tables\Columns\TextColumn::make('km_interval')->label('Periyod (KM)')->sortable(),
                Tables\Columns\TextColumn::make('warning_km')->label('Xəbərdarlıq (KM)')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceRules::route('/'),
            'create' => Pages\CreateMaintenanceRule::route('/create'),
            'edit' => Pages\EditMaintenanceRule::route('/{record}/edit'),
        ];
    }
}
