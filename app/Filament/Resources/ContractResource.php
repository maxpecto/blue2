<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use App\Models\Motorcycle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Müqavilə';
    protected static ?string $pluralModelLabel = 'Müqavilələr';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tərəflər')
                    ->schema([
                        Forms\Components\Select::make('courier_id')
                            ->label('Kurye')
                            ->relationship('courier', 'ad')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->ad} {$record->soyad} - {$record->fin_kodu}")
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('motorcycle_id')
                            ->label('Motosiklet')
                            ->options(
                                Motorcycle::where('status', 'depoda')->pluck('plaka', 'id')
                            )
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Müqavilə Şərtləri')
                    ->schema([
                        Forms\Components\TextInput::make('sozlesme_no')
                            ->label('Müqavilə Nömrəsi')
                            ->default('M-' . Str::random(8))
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label('Müqavilə Tipi')
                            ->options([
                                'kiralama' => 'İcarə',
                                'taksitli_satis' => 'Taksitli Satış',
                            ])
                            ->reactive()
                            ->required(),
                        Forms\Components\DatePicker::make('baslangic_tarihi')
                            ->label('Başlanğıc Tarixi')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $duration = $get('duration_months');
                                if (blank($state) || blank($duration)) {
                                    return;
                                }
                                $set('bitis_tarihi', Carbon::parse($state)->addMonths((int)$duration)->format('Y-m-d'));
                            })
                            ->required(),
                        Forms\Components\TextInput::make('duration_months')
                            ->label('Müddət (ay)')
                            ->numeric()
                            ->reactive()
                            ->dehydrated(false)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                // Bitiş tarihini hesapla
                                $startDate = $get('baslangic_tarihi');
                                if (!blank($state) && !blank($startDate)) {
                                    $set('bitis_tarihi', Carbon::parse($startDate)->addMonths((int)$state)->format('Y-m-d'));
                                }

                                // Periodik ödemeyi hesapla
                                $totalAmount = (float) $get('toplam_tutar');
                                $durationMonths = (int) $state;
                                $paymentPeriodDays = (int) $get('odeme_periyodu');
                                $contractType = $get('type');
                                $initialPayment = (float) $get('initial_payment');

                                $remainingAmount = $contractType === 'taksitli_satis' ? $totalAmount - $initialPayment : $totalAmount;

                                if ($remainingAmount >= 0 && $durationMonths && $paymentPeriodDays) {
                                    $totalDays = $durationMonths * 30;
                                    $numberOfPayments = floor($totalDays / $paymentPeriodDays);
                                    if ($numberOfPayments > 0) {
                                        $set('aylik_odeme', round($remainingAmount / $numberOfPayments, 2));
                                    }
                                }
                            })
                            ->required(),
                        Forms\Components\Hidden::make('bitis_tarihi'),
                    ])->columns(2),

                Forms\Components\Section::make('Maliyyə Məlumatları')
                    ->schema([
                        Forms\Components\TextInput::make('toplam_tutar')
                            ->label('Toplam Məbləğ')
                            ->numeric()->prefix('₼')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $totalAmount = (float) $get('toplam_tutar');
                                $initialPayment = (float) $get('initial_payment');
                                $durationMonths = (int) $get('duration_months');
                                $paymentPeriodDays = (int) $get('odeme_periyodu');
                                $contractType = $get('type');

                                $remainingAmount = $contractType === 'taksitli_satis' ? $totalAmount - $initialPayment : $totalAmount;

                                if ($remainingAmount >= 0 && $durationMonths && $paymentPeriodDays) {
                                    $totalDays = $durationMonths * 30;
                                    $numberOfPayments = floor($totalDays / $paymentPeriodDays);
                                    if ($numberOfPayments > 0) {
                                        $set('aylik_odeme', round($remainingAmount / $numberOfPayments, 2));
                                    }
                                }
                            })
                            ->required(),
                        Forms\Components\TextInput::make('initial_payment')
                            ->label(fn (Get $get): string => match ($get('type')) {
                                'kiralama' => 'Depozito',
                                'taksitli_satis' => 'İlkin Ödəniş',
                                default => 'İlkin Ödəniş / Depozito',
                            })
                            ->numeric()->prefix('₼')->default(0)
                            ->reactive()
                            ->visible(fn (Get $get): bool => !empty($get('type')))
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $totalAmount = (float) $get('toplam_tutar');
                                $initialPayment = (float) $get('initial_payment');
                                $durationMonths = (int) $get('duration_months');
                                $paymentPeriodDays = (int) $get('odeme_periyodu');
                                $contractType = $get('type');

                                $remainingAmount = $contractType === 'taksitli_satis' ? $totalAmount - $initialPayment : $totalAmount;

                                if ($remainingAmount >= 0 && $durationMonths && $paymentPeriodDays) {
                                    $totalDays = $durationMonths * 30;
                                    $numberOfPayments = floor($totalDays / $paymentPeriodDays);
                                    if ($numberOfPayments > 0) {
                                        $set('aylik_odeme', round($remainingAmount / $numberOfPayments, 2));
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('aylik_odeme')
                            ->label('Periodik Ödəniş Məbləği')
                            ->numeric()->prefix('₼')->required(),
                        Forms\Components\TextInput::make('odeme_periyodu')
                            ->label('Ödəmə Periyodu (Gün)')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $totalAmount = (float) $get('toplam_tutar');
                                $durationMonths = (int) $get('duration_months');
                                $paymentPeriodDays = (int) $get('odeme_periyodu');
                                $contractType = $get('type');
                                $initialPayment = (float) $get('initial_payment');

                                $remainingAmount = $contractType === 'taksitli_satis' ? $totalAmount - $initialPayment : $totalAmount;

                                if ($remainingAmount >= 0 && $durationMonths && $paymentPeriodDays) {
                                    $totalDays = $durationMonths * 30;
                                    $numberOfPayments = floor($totalDays / $paymentPeriodDays);
                                    if ($numberOfPayments > 0) {
                                        $set('aylik_odeme', round($remainingAmount / $numberOfPayments, 2));
                                    }
                                }
                            })
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Sənədlər və Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktiv',
                                'tamamlandi' => 'Tamamlanıb',
                                'iptal_edildi' => 'Ləğv Edilib',
                            ])
                            ->default('aktif')
                            ->required(),
                        Forms\Components\FileUpload::make('teslimat_gorselleri')
                            ->label('Təhvil-Təslim Görsəlləri')
                            ->multiple()
                            ->image()
                            ->directory('contract-delivery-images')
                            ->preserveFilenames(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sozlesme_no')->label('Müqavilə No')->searchable(),
                Tables\Columns\TextColumn::make('courier.ad')
                    ->label('Kurye')
                    ->formatStateUsing(fn ($state, $record) => "{$record->courier->ad} {$record->courier->soyad}")
                    ->searchable(),
                Tables\Columns\TextColumn::make('motorcycle.plaka')->label('Motosiklet Plakası')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Tip')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kiralama' => 'İcarə',
                        'taksitli_satis' => 'Taksitli Satış',
                    }),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tamamlandi' => 'primary',
                        'iptal_edildi' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('downloadPdf')
                    ->label('PDF Yüklə')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Contract $record) {
                        $pdf = Pdf::loadView('pdf.contract', ['contract' => $record]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, $record->sozlesme_no . '.pdf');
                    }),
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
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
