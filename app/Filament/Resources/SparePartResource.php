<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SparePartResource\Pages;
use App\Filament\Resources\SparePartResource\RelationManagers;
use App\Models\SparePart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SparePartResource extends Resource
{
    protected static ?string $model = SparePart::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $modelLabel = 'Ehtiyat Hissəsi';
    protected static ?string $pluralModelLabel = 'Ehtiyat Hissələri';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('parca_adi')
                    ->label('Hissənin Adı')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('parca_kodu')
                    ->label('Parça Kodu (SKU)')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('marka')
                    ->label('Marka'),
                Forms\Components\Textarea::make('uyumlu_modeller')
                    ->label('Uyğun Olduğu Modellər')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('alis_fiyati')
                    ->label('Alış Qiyməti')
                    ->numeric()
                    ->prefix('₼')
                    ->required(),
                Forms\Components\TextInput::make('satis_fiyati')
                    ->label('Satış Qiyməti')
                    ->numeric()
                    ->prefix('₼')
                    ->required(),
                Forms\Components\TextInput::make('stok_adedi')
                    ->label('Stok Ədədi')
                    ->numeric()
                    ->required()
                    ->default(0),
                Forms\Components\TextInput::make('minimum_stok_seviyesi')
                    ->label('Minimum Stok Səviyyəsi (Uyarı üçün)')
                    ->numeric()
                    ->required()
                    ->default(0),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parca_adi')
                    ->label('Hissə Adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parca_kodu')
                    ->label('Kod')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stok_adedi')
                    ->label('Stok')
                    ->sortable(),
                Tables\Columns\TextColumn::make('alis_fiyati')
                    ->label('Alış Qiyməti')
                    ->money('AZN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('satis_fiyati')
                    ->label('Satış Qiyməti')
                    ->money('AZN')
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpareParts::route('/'),
            'create' => Pages\CreateSparePart::route('/create'),
            'edit' => Pages\EditSparePart::route('/{record}/edit'),
        ];
    }
}
