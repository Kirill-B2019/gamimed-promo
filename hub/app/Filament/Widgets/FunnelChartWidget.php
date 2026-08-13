<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsStats;
use Filament\Widgets\ChartWidget;

class FunnelChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Conversion funnel';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $funnel = AnalyticsStats::fromSession()->funnelCounts();

        return [
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => [
                        $funnel['cta_click'],
                        $funnel['calculator_use'],
                        $funnel['contact_submit'],
                    ],
                    'backgroundColor' => [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                    ],
                ],
            ],
            'labels' => ['CTA click', 'Calculator use', 'Contact submit'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
