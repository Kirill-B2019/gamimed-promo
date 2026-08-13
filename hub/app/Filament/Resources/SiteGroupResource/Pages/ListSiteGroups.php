<?php

namespace App\Filament\Resources\SiteGroupResource\Pages;

use App\Filament\Resources\SiteGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteGroups extends ListRecords
{
    protected static string $resource = SiteGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
