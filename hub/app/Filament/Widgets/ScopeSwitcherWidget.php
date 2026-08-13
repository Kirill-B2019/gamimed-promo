<?php

namespace App\Filament\Widgets;

use App\Models\Site;
use App\Models\SiteGroup;
use App\Services\AdminScope;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class ScopeSwitcherWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.scope-switcher';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public static function canView(): bool
    {
        return auth()->check();
    }

    public function mount(): void
    {
        $scope = AdminScope::fromSession(auth()->user());

        $this->form->fill([
            'type' => $scope->type,
            'group_id' => $scope->groupId,
            'site_id' => $scope->siteId,
        ]);
    }

    public function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Scope')
                    ->options([
                        AdminScope::TYPE_ALL => 'All',
                        AdminScope::TYPE_GROUP => 'Group',
                        AdminScope::TYPE_SITE => 'Site',
                    ])
                    ->required()
                    ->live()
                    ->native(false),
                Forms\Components\Select::make('group_id')
                    ->label('Group')
                    ->options(
                        SiteGroup::query()
                            ->when(
                                ! $user?->isSuperAdmin(),
                                fn ($q) => $q->whereHas(
                                    'sites',
                                    fn ($sites) => $sites->whereIn('sites.id', $user?->accessibleSiteIds() ?? [])
                                )
                            )
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->visible(fn (Forms\Get $get) => $get('type') === AdminScope::TYPE_GROUP)
                    ->required(fn (Forms\Get $get) => $get('type') === AdminScope::TYPE_GROUP)
                    ->native(false),
                Forms\Components\Select::make('site_id')
                    ->label('Site')
                    ->options(
                        Site::query()
                            ->when(! $user?->isSuperAdmin(), fn ($q) => $q->whereIn('id', $user?->accessibleSiteIds() ?? []))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->visible(fn (Forms\Get $get) => $get('type') === AdminScope::TYPE_SITE)
                    ->required(fn (Forms\Get $get) => $get('type') === AdminScope::TYPE_SITE)
                    ->native(false),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function apply(): void
    {
        $data = $this->form->getState();

        $scope = (new AdminScope(
            type: $data['type'] ?? AdminScope::TYPE_ALL,
            groupId: isset($data['group_id']) ? (int) $data['group_id'] : null,
            siteId: isset($data['site_id']) ? (int) $data['site_id'] : null,
        ))->constrainForUser(auth()->user());

        $scope->store();

        Notification::make()
            ->title('Scope updated')
            ->body($scope->label())
            ->success()
            ->send();
    }
}
