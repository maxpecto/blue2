<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotorcycleResource\Pages;
use App\Filament\Resources\MotorcycleResource\RelationManagers;
use App\Models\Motorcycle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MotorcycleResource extends Resource
{
    protected static ?string $model = Motorcycle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Motosiklet';
    protected static ?string $pluralModelLabel = 'Motosikletlər';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Əsas Məlumatlar')
                    ->schema([
                        Forms\Components\Select::make('investor_id')
                            ->label('İnvestor')
                            ->relationship('investor', 'ad')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->ad} {$record->soyad}")
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('plaka')
                            ->label('Dövlət Nömrə Nişanı')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('marka')
                            ->label('Marka')
                            ->required(),
                        Forms\Components\TextInput::make('model')
                            ->label('Model')
                            ->required(),
                        Forms\Components\TextInput::make('renk')
                            ->label('Rəng')
                            ->required(),
                        Forms\Components\TextInput::make('tip')
                            ->label('Tip (Moped, Motosikl)')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Texniki Məlumatlar')
                    ->schema([
                        Forms\Components\TextInput::make('sase_no')
                            ->label('Karkas (Şassi) Nömrəsi')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('motor_no')
                            ->label('Mühərrik Nömrəsi')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('motor_hacmi')
                            ->label('Mühərrik Həcmi')
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Alış Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('alis_fiyati')
                            ->label('Alış Qiyməti')
                            ->numeric()
                            ->prefix('₼')
                            ->required(),
                        Forms\Components\TextInput::make('alis_km')
                            ->label('Alındığı KM')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('mevcut_km')
                            ->label('Mövcud KM')
                            ->numeric()
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Sistem və Sənədlər')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'depoda' => 'Anbarda',
                                'kirada' => 'İcarədə',
                                'satildi' => 'Satılıb',
                                'serviste' => 'Servisdə',
                                'pert' => 'Təmirə Yararsız',
                            ])
                            ->required()
                            ->default('depoda'),
                        Forms\Components\FileUpload::make('gorseller')
                            ->label('Motosiklet Görselləri')
                            ->multiple()
                            ->image()
                            ->directory('motorcycle-images')
                            ->preserveFilenames(),
                        Forms\Components\FileUpload::make('belgeler')
                            ->label('Sənədlər (Texpasport, Sığorta)')
                            ->multiple()
                            ->directory('motorcycle-documents')
                            ->preserveFilenames(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plaka')
                    ->label('Plaka')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marka')
                    ->label('Marka')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('investor.ad')
                    ->label('İnvestor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'depoda' => 'primary',
                        'kirada' => 'success',
                        'serviste' => 'warning',
                        'satildi' => 'gray',
                        'pert' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MileageLogsRelationManager::class,
            RelationManagers\MaintenanceLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMotorcycles::route('/'),
            'create' => Pages\CreateMotorcycle::route('/create'),
            'edit' => Pages\EditMotorcycle::route('/{record}/edit'),
        ];
    }
}
