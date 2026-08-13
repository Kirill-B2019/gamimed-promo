<?php

namespace App\Services;

use App\Enums\ContentStatus;
use App\Models\ContentSection;
use App\Models\Site;
use Illuminate\Support\Collection;

class ContentResolver
{
    /**
     * Resolve published sections for a site with priority: site > group > global.
     *
     * @return array<int, array{section_key: string, locale: string, payload: array<string, mixed>, source: string}>
     */
    public function resolve(Site $site, string $locale): array
    {
        $groupIds = $site->groups()->pluck('site_groups.id');

        $sections = ContentSection::query()
            ->where('status', ContentStatus::Published)
            ->where('locale', $locale)
            ->where(function ($query) use ($site, $groupIds) {
                $query->where('site_id', $site->id);

                if ($groupIds->isNotEmpty()) {
                    $query->orWhere(function ($groupQuery) use ($groupIds) {
                        $groupQuery
                            ->whereNull('site_id')
                            ->whereIn('site_group_id', $groupIds);
                    });
                }

                $query->orWhere(function ($globalQuery) {
                    $globalQuery
                        ->whereNull('site_id')
                        ->whereNull('site_group_id');
                });
            })
            ->orderBy('section_key')
            ->get();

        return $this->mergeByPriority($sections);
    }

    /**
     * @param  Collection<int, ContentSection>  $sections
     * @return array<int, array{section_key: string, locale: string, payload: array<string, mixed>, source: string}>
     */
    private function mergeByPriority(Collection $sections): array
    {
        $resolved = [];

        foreach ($sections as $section) {
            $key = $section->section_key;
            $priority = $this->scopePriority($section);

            if (! isset($resolved[$key]) || $priority > $resolved[$key]['_priority']) {
                $resolved[$key] = [
                    'section_key' => $section->section_key,
                    'locale' => $section->locale,
                    'payload' => $section->payload ?? [],
                    'source' => $section->scopeLabel(),
                    '_priority' => $priority,
                ];
            }
        }

        return array_values(array_map(function (array $item) {
            unset($item['_priority']);

            return $item;
        }, $resolved));
    }

    private function scopePriority(ContentSection $section): int
    {
        if ($section->isSiteOverride()) {
            return 3;
        }

        if ($section->isGroupScoped()) {
            return 2;
        }

        return 1;
    }
}
