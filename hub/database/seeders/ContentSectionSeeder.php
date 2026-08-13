<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\ContentSection;
use App\Models\Site;
use Illuminate\Database\Seeder;
use RuntimeException;

class ContentSectionSeeder extends Seeder
{
    public function run(): void
    {
        $arab = Site::query()->where('slug', 'arab')->firstOrFail();
        $china = Site::query()->where('slug', 'china')->firstOrFail();

        $this->forgetLegacySharedCopies();
        $this->seedSiteSections($arab, $this->loadSections('arab_sections.php'));
        $this->seedSiteSections($china, $this->loadSections('china_sections.php'));
    }

    /**
     * Previous seeds stored incomplete English stubs at group scope. Site payloads
     * replace the whole section, so leftover group rows would only confuse Filament.
     */
    private function forgetLegacySharedCopies(): void
    {
        ContentSection::query()
            ->whereNull('site_id')
            ->whereIn('section_key', [
                'hero',
                'about',
                'tokenomics',
                'roadmap',
                'team',
                'partners',
                'security',
                'sharia',
                'technology',
                'testimonials',
                'faq',
                'contact',
                'footer',
            ])
            ->delete();
    }

    /**
     * @param  array<string, array<string, array<string, mixed>>>  $byLocale
     */
    private function seedSiteSections(Site $site, array $byLocale): void
    {
        foreach ($byLocale as $locale => $sections) {
            foreach ($sections as $sectionKey => $payload) {
                ContentSection::query()->updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'site_group_id' => null,
                        'section_key' => $sectionKey,
                        'locale' => $locale,
                    ],
                    [
                        'payload' => $payload,
                        'status' => ContentStatus::Published,
                    ]
                );
            }
        }
    }

    /**
     * @return array<string, array<string, array<string, mixed>>>
     */
    private function loadSections(string $filename): array
    {
        $path = database_path('data/'.$filename);

        if (! is_file($path)) {
            throw new RuntimeException("Missing content seed file: {$path}");
        }

        /** @var array<string, array<string, array<string, mixed>>> $sections */
        $sections = require $path;

        return $sections;
    }
}
