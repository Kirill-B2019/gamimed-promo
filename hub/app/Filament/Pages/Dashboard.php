<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FunnelChartWidget;
use App\Filament\Widgets\PeriodFilterWidget;
use App\Filament\Widgets\ScopeSwitcherWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            ScopeSwitcherWidget::class,
            PeriodFilterWidget::class,
            StatsOverviewWidget::class,
            FunnelChartWidget::class,
        ];
    }
}
