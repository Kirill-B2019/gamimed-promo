<?php

namespace App\Services;

use App\Models\Site;
use App\Models\SiteGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class AdminScope
{
    public const SESSION_KEY = 'hub_admin_scope';

    public const TYPE_ALL = 'all';

    public const TYPE_GROUP = 'group';

    public const TYPE_SITE = 'site';

    public function __construct(
        public readonly string $type = self::TYPE_ALL,
        public readonly ?int $groupId = null,
        public readonly ?int $siteId = null,
    ) {}

    public static function fromSession(?User $user = null): self
    {
        $payload = Session::get(self::SESSION_KEY, [
            'type' => self::TYPE_ALL,
            'group_id' => null,
            'site_id' => null,
        ]);

        $scope = new self(
            type: $payload['type'] ?? self::TYPE_ALL,
            groupId: isset($payload['group_id']) ? (int) $payload['group_id'] : null,
            siteId: isset($payload['site_id']) ? (int) $payload['site_id'] : null,
        );

        return $scope->constrainForUser($user ?? auth()->user());
    }

    public function store(): void
    {
        Session::put(self::SESSION_KEY, [
            'type' => $this->type,
            'group_id' => $this->groupId,
            'site_id' => $this->siteId,
        ]);
    }

    public function constrainForUser(?User $user): self
    {
        if (! $user || $user->isSuperAdmin()) {
            return $this;
        }

        $allowed = $user->accessibleSiteIds();

        if ($this->type === self::TYPE_SITE && $this->siteId && in_array($this->siteId, $allowed, true)) {
            return $this;
        }

        if (count($allowed) === 1) {
            return new self(self::TYPE_SITE, null, $allowed[0]);
        }

        return new self(self::TYPE_ALL, null, null);
    }

    /**
     * @return list<int>
     */
    public function siteIds(?User $user = null): array
    {
        $user ??= auth()->user();

        $ids = match ($this->type) {
            self::TYPE_SITE => $this->siteId ? [$this->siteId] : [],
            self::TYPE_GROUP => $this->groupId
                ? SiteGroup::query()->find($this->groupId)?->sites()->pluck('sites.id')->all() ?? []
                : [],
            default => Site::query()->pluck('id')->all(),
        };

        if ($user && ! $user->isSuperAdmin()) {
            $ids = array_values(array_intersect($ids, $user->accessibleSiteIds()));
        }

        return $ids;
    }

    public function applyToSites(Builder $query, ?User $user = null): Builder
    {
        return $query->whereIn('id', $this->siteIds($user));
    }

    public function applyToContentSections(Builder $query, ?User $user = null): Builder
    {
        $siteIds = $this->siteIds($user);

        if ($siteIds === []) {
            return $query->whereRaw('1 = 0');
        }

        $groupIds = match ($this->type) {
            self::TYPE_GROUP => $this->groupId ? [$this->groupId] : [],
            default => SiteGroup::query()
                ->whereHas('sites', fn (Builder $sites) => $sites->whereIn('sites.id', $siteIds))
                ->pluck('id')
                ->all(),
        };

        return $query->where(function (Builder $scopeQuery) use ($siteIds, $groupIds) {
            $scopeQuery->where(function (Builder $global) {
                $global->whereNull('site_id')->whereNull('site_group_id');
            });

            if ($groupIds !== []) {
                $scopeQuery->orWhere(function (Builder $group) use ($groupIds) {
                    $group->whereNull('site_id')->whereIn('site_group_id', $groupIds);
                });
            }

            $scopeQuery->orWhereIn('site_id', $siteIds);
        });
    }

    public function applyToContactMessages(Builder $query, ?User $user = null): Builder
    {
        return $query->whereIn('site_id', $this->siteIds($user));
    }

    public function applyToAnalyticsEvents(Builder $query, ?User $user = null): Builder
    {
        return $query->whereIn('site_id', $this->siteIds($user));
    }

    public function label(): string
    {
        return match ($this->type) {
            self::TYPE_SITE => 'Site: '.(Site::query()->find($this->siteId)?->name ?? '—'),
            self::TYPE_GROUP => 'Group: '.(SiteGroup::query()->find($this->groupId)?->name ?? '—'),
            default => 'All sites',
        };
    }
}
