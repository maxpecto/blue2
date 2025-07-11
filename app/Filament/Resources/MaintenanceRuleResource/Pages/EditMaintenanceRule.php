<?php

namespace App\Filament\Resources\MaintenanceRuleResource\Pages;

use App\Filament\Resources\MaintenanceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceRule extends EditRecord
{
    protected static string $resource = MaintenanceRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
