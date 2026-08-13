<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsStats;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $stats = AnalyticsStats::fromSession();

        return [
            Stat::make('Page views', number_format($stats->eventCount('page_view')))
                ->description($stats->periodLabel())
                ->icon('heroicon-o-eye'),
            Stat::make('Leads', number_format($stats->leadsCount()))
                ->description($stats->newLeadsCount().' new')
                ->icon('heroicon-o-inbox'),
            Stat::make('Whitepaper downloads', number_format($stats->eventCount('whitepaper_download')))
                ->description($stats->periodLabel())
                ->icon('heroicon-o-document-arrow-down'),
            Stat::make('Published sections', number_format($stats->contentCounts()['published']))
                ->description($stats->contentCounts()['draft'].' drafts')
                ->icon('heroicon-o-document-text'),
        ];
    }
}
