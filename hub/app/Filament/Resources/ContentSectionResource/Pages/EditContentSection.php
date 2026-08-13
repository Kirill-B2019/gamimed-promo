<?php

namespace App\Filament\Resources\ContentSectionResource\Pages;

use App\Filament\Resources\ContentSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentSection extends EditRecord
{
    protected static string $resource = ContentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()?->can('delete', $this->record)),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $scopeType = $this->form->getState()['scope_type'] ?? 'global';

        return match ($scopeType) {
            'group' => array_merge($data, ['site_id' => null]),
            'site' => array_merge($data, ['site_group_id' => null]),
            default => array_merge($data, ['site_id' => null, 'site_group_id' => null]),
        };
    }
}
