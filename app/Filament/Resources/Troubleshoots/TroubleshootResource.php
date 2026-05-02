<?php

namespace App\Filament\Resources\Troubleshoots;

use App\Filament\Resources\Troubleshoots\Pages\CreateTroubleshoot;
use App\Filament\Resources\Troubleshoots\Pages\EditTroubleshoot;
use App\Filament\Resources\Troubleshoots\Pages\ListTroubleshoots;
use App\Filament\Resources\Troubleshoots\Schemas\TroubleshootForm;
use App\Filament\Resources\Troubleshoots\Tables\TroubleshootsTable;
use App\Filament\Resources\Troubleshoots\Widgets\TroubleStat;
use App\Models\Troubleshoot;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TroubleshootResource extends Resource
{
    protected static ?string $model = Troubleshoot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TroubleshootForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TroubleshootsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TroubleStat::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTroubleshoots::route('/'),
            'create' => CreateTroubleshoot::route('/create'),
            'edit' => EditTroubleshoot::route('/{record}/edit'),
        ];
    }
}
