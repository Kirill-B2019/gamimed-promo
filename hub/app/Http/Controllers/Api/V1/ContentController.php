<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HubSetting;
use App\Models\Site;
use App\Services\ContentResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function __construct(
        private readonly ContentResolver $contentResolver,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var Site $site */
        $site = $request->user();

        $locale = $request->string('locale')->toString() ?: $site->default_locale;

        $globalSettings = HubSetting::globalValues();
        $siteSettings = array_merge($globalSettings, $site->settings ?? []);

        return response()->json([
            'site' => [
                'slug' => $site->slug,
                'name' => $site->name,
                'locale' => $locale,
                'locales' => $site->locales ?? [],
                'settings' => $siteSettings,
            ],
            'sections' => $this->contentResolver->resolve($site, $locale),
        ]);
    }
}
