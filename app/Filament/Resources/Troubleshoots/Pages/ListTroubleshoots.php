<?php

namespace App\Filament\Resources\Troubleshoots\Pages;

use App\Filament\Resources\Troubleshoots\TroubleshootResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTroubleshoots extends ListRecords
{
    protected static string $resource = TroubleshootResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return TroubleshootResource::getWidgets();
    }
}
