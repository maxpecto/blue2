<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourierResource\Pages;
use App\Filament\Resources\CourierResource\RelationManagers;
use App\Models\Courier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $modelLabel = 'Kurye';
    protected static ?string $pluralModelLabel = 'Kuryeler';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Şəxsi Məlumatlar')
                    ->schema([
                        Forms\Components\TextInput::make('ad')
                            ->label('Ad')
                            ->required(),
                        Forms\Components\TextInput::make('soyad')
                            ->label('Soyad')
                            ->required(),
                        Forms\Components\TextInput::make('ata_adi')
                            ->label('Ata Adı')
                            ->required(),
                        Forms\Components\DatePicker::make('dogum_tarihi')
                            ->label('Doğum Tarihi')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Sənəd Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('seria_no')
                            ->label('Şəxsiyyət Vəsiqəsi Nömrəsi')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('fin_kodu')
                            ->label('FIN Kodu')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])->columns(2),

                Forms\Components\Section::make('Əlaqə Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('telefon_no')
                            ->label('Telefon Nömrəsi')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('whatsapp_no')
                            ->label('WhatsApp Nömrəsi')
                            ->tel(),
                        Forms\Components\Textarea::make('adres_1')
                            ->label('Ünvan 1 (Zəruri)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('adres_2')
                            ->label('Ünvan 2')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Yaxınlar')
                    ->schema([
                        Forms\Components\TextInput::make('akraba_tel_1')
                            ->label('Yaxın Şəxs Telefon 1 (Zəruri)')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('akraba_tel_2')
                            ->label('Yaxın Şəxs Telefon 2')
                            ->tel(),
                        Forms\Components\TextInput::make('akraba_tel_3')
                            ->label('Yaxın Şəxs Telefon 3')
                            ->tel(),
                    ])->columns(3),

                Forms\Components\Section::make('Sistem')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktiv',
                                'pasif' => 'Passiv',
                                'kara_listede' => 'Qara Siyahı',
                            ])
                            ->required()
                            ->default('aktif'),
                        Forms\Components\FileUpload::make('belgeler')
                            ->label('Sənədlər (Ehliyyət, Ş.V. və s.)')
                            ->multiple()
                            ->directory('courier-documents')
                            ->preserveFilenames(),
                    ]),
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
                Tables\Columns\TextColumn::make('telefon_no')
                    ->label('Telefon'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'pasif' => 'warning',
                        'kara_listede' => 'danger',
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCouriers::route('/'),
            'create' => Pages\CreateCourier::route('/create'),
            'edit' => Pages\EditCourier::route('/{record}/edit'),
        ];
    }
}
