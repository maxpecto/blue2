<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestorResource\Pages;
use App\Filament\Resources\InvestorResource\RelationManagers;
use App\Models\Investor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestorResource extends Resource
{
    protected static ?string $model = Investor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Yatırımcı';
    protected static ?string $pluralModelLabel = 'Yatırımcılar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ad')
                    ->label('Ad')
                    ->required(),
                Forms\Components\TextInput::make('soyad')
                    ->label('Soyad')
                    ->required(),
                Forms\Components\TextInput::make('fin_kodu')
                    ->label('FIN Kodu')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('seria_no')
                    ->label('Şəxsiyyət Vəsiqəsi Nömrəsi')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\DatePicker::make('dogum_tarihi')
                    ->label('Doğum Tarihi')
                    ->required(),
                Forms\Components\TextInput::make('telefon')
                    ->label('Telefon')
                    ->tel(),
                Forms\Components\TextInput::make('email')
                    ->label('E-poçt')
                    ->email()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ad')
                    ->label('Ad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('soyad')
                    ->label('Soyad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fin_kodu')
                    ->label('FIN Kodu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefon')
                    ->label('Telefon'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Əlavə Edilmə Tarixi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListInvestors::route('/'),
            'create' => Pages\CreateInvestor::route('/create'),
            'edit' => Pages\EditInvestor::route('/{record}/edit'),
        ];
    }
}
