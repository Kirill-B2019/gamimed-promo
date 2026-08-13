<?php

namespace App\Filament\Resources\ContentSectionResource\Pages;

use App\Filament\Resources\ContentSectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentSection extends CreateRecord
{
    protected static string $resource = ContentSectionResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->applyScopeFromForm($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function applyScopeFromForm(array $data): array
    {
        $scopeType = $this->form->getState()['scope_type'] ?? 'global';

        return match ($scopeType) {
            'group' => array_merge($data, ['site_id' => null]),
            'site' => array_merge($data, ['site_group_id' => null]),
            default => array_merge($data, ['site_id' => null, 'site_group_id' => null]),
        };
    }
}
