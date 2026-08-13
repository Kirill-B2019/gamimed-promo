<?php

namespace App\Filament\Resources\SiteGroupResource\Pages;

use App\Filament\Resources\SiteGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteGroup extends EditRecord
{
    protected static string $resource = SiteGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()?->isSuperAdmin()),
        ];
    }
}
