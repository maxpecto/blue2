<?php

namespace App\Filament\Resources\MaintenanceRuleResource\Pages;

use App\Filament\Resources\MaintenanceRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceRules extends ListRecords
{
    protected static string $resource = MaintenanceRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
