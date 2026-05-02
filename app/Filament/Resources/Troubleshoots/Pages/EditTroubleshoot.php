<?php

namespace App\Filament\Resources\Troubleshoots\Pages;

use App\Filament\Resources\Troubleshoots\TroubleshootResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTroubleshoot extends EditRecord
{
    protected static string $resource = TroubleshootResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
