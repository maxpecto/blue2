<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\Motorcycle;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Önce sözleşmeyi oluştur
        $contract = static::getModel()::create($data);

        // Motosikletin durumunu güncelle
        $motorcycle = Motorcycle::find($data['motorcycle_id']);
        if ($motorcycle) {
            $motorcycle->status = 'kirada';
            $motorcycle->save();
        }

        // Ödeme takvimini oluştur
        $startDate = Carbon::parse($data['baslangic_tarihi']);
        $endDate = Carbon::parse($data['bitis_tarihi']);
        $paymentPeriod = (int)$data['odeme_periyodu'];
        $paymentAmount = $data['aylik_odeme'];

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            Payment::create([
                'contract_id' => $contract->id,
                'vade_tarihi' => $currentDate->toDateString(),
                'tutar' => $paymentAmount,
                'status' => 'odenmedi',
            ]);
            $currentDate->addDays($paymentPeriod);
        }

        return $contract;
    }
}
