<?php

namespace App\Filament\Resources\MotorcycleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MileageLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'mileageLogs';

    protected static ?string $title = 'Kilometre Geçmişi';
    protected static ?string $modelLabel = 'Kilometre Kaydı';
    protected static ?string $pluralModelLabel = 'Kilometre Kayıtları';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('km')
                    ->label('Kilometre')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notlar')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('km')
            ->columns([
                Tables\Columns\TextColumn::make('km')->label('Kilometre')->sortable(),
                Tables\Columns\TextColumn::make('notes')->label('Notlar'),
                Tables\Columns\TextColumn::make('created_at')->label('Tarih')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
