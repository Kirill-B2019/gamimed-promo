<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsStats;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class PeriodFilterWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.period-filter';

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public static function canView(): bool
    {
        return auth()->check();
    }

    public function mount(): void
    {
        $stats = AnalyticsStats::fromSession();

        $this->form->fill([
            'period' => $stats->period,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('period')
                    ->label('Stats period')
                    ->options([
                        AnalyticsStats::PERIOD_7D => 'Last 7 days',
                        AnalyticsStats::PERIOD_30D => 'Last 30 days',
                        AnalyticsStats::PERIOD_90D => 'Last 90 days',
                    ])
                    ->required()
                    ->native(false),
            ])
            ->columns(1)
            ->statePath('data');
    }

    public function apply(): void
    {
        $data = $this->form->getState();
        AnalyticsStats::storePeriod($data['period'] ?? AnalyticsStats::PERIOD_30D);

        Notification::make()
            ->title('Period updated')
            ->success()
            ->send();

        $this->dispatch('stats-period-updated');
    }
}
