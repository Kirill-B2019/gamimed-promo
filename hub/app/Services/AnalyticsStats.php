<?php

namespace App\Services;

use App\Enums\ContentStatus;
use App\Enums\LeadStatus;
use App\Models\AnalyticsEvent;
use App\Models\ContactMessage;
use App\Models\ContentSection;
use App\Models\SiteGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsStats
{
    public const PERIOD_7D = '7d';

    public const PERIOD_30D = '30d';

    public const PERIOD_90D = '90d';

    public const SESSION_KEY = 'hub_admin_period';

    public function __construct(
        public readonly AdminScope $scope,
        public readonly string $period = self::PERIOD_30D,
    ) {}

    public static function fromSession(?AdminScope $scope = null): self
    {
        $period = session(self::SESSION_KEY, self::PERIOD_30D);

        return new self(
            scope: $scope ?? AdminScope::fromSession(),
            period: in_array($period, [self::PERIOD_7D, self::PERIOD_30D, self::PERIOD_90D], true)
                ? $period
                : self::PERIOD_30D,
        );
    }

    public static function storePeriod(string $period): void
    {
        session([self::SESSION_KEY => $period]);
    }

    public function since(): Carbon
    {
        return match ($this->period) {
            self::PERIOD_7D => now()->subDays(7),
            self::PERIOD_90D => now()->subDays(90),
            default => now()->subDays(30),
        };
    }

    public function periodLabel(): string
    {
        return match ($this->period) {
            self::PERIOD_7D => 'Last 7 days',
            self::PERIOD_90D => 'Last 90 days',
            default => 'Last 30 days',
        };
    }

    public function eventCount(string $eventType): int
    {
        return $this->eventsQuery()
            ->where('event_type', $eventType)
            ->count();
    }

    /**
     * @return array<string, int>
     */
    public function funnelCounts(): array
    {
        $types = ['cta_click', 'calculator_use', 'contact_submit'];

        $counts = $this->eventsQuery()
            ->whereIn('event_type', $types)
            ->selectRaw('event_type, COUNT(*) as total')
            ->groupBy('event_type')
            ->pluck('total', 'event_type');

        return [
            'cta_click' => (int) ($counts['cta_click'] ?? 0),
            'calculator_use' => (int) ($counts['calculator_use'] ?? 0),
            'contact_submit' => (int) ($counts['contact_submit'] ?? 0),
        ];
    }

    public function leadsCount(): int
    {
        return $this->leadsQuery()->count();
    }

    public function newLeadsCount(): int
    {
        return $this->leadsQuery()
            ->where('status', LeadStatus::New->value)
            ->count();
    }

    /**
     * @return array{published: int, draft: int}
     */
    public function contentCounts(): array
    {
        $query = $this->contentQuery();

        return [
            'published' => (clone $query)->where('status', ContentStatus::Published)->count(),
            'draft' => (clone $query)->where('status', ContentStatus::Draft)->count(),
        ];
    }

    private function eventsQuery(): Builder
    {
        $query = AnalyticsEvent::query()->where('created_at', '>=', $this->since());

        return $this->scope->applyToAnalyticsEvents($query);
    }

    private function leadsQuery(): Builder
    {
        $query = ContactMessage::query()->where('created_at', '>=', $this->since());

        return $this->scope->applyToContactMessages($query);
    }

    private function contentQuery(): Builder
    {
        return $this->scope->applyToContentSections(ContentSection::query());
    }

    /**
     * @return list<int>
     */
    public function scopedGroupIds(): array
    {
        $siteIds = $this->scope->siteIds();

        if ($this->scope->type === AdminScope::TYPE_GROUP && $this->scope->groupId) {
            return [$this->scope->groupId];
        }

        return SiteGroup::query()
            ->whereHas('sites', fn (Builder $q) => $q->whereIn('sites.id', $siteIds))
            ->pluck('id')
            ->all();
    }
}
