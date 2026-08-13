<?php

namespace App\Http\Controllers;

use App\Services\HubClient;
use App\Support\SeoMeta;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function terms(HubClient $hub): View
    {
        return $this->page($hub, 'terms');
    }

    public function privacy(HubClient $hub): View
    {
        return $this->page($hub, 'privacy');
    }

    private function page(HubClient $hub, string $key): View
    {
        $hub->trackEvent('page_view', [
            'path' => request()->getPathInfo(),
            'meta' => ['page' => $key],
        ]);

        $body = trans("legal.{$key}.body");
        $paragraphs = is_array($body) ? $body : [$body];

        return view('legal.show', [
            'heading' => __("legal.{$key}.title"),
            'paragraphs' => $paragraphs,
            'seo' => SeoMeta::make(
                __("legal.{$key}.title"),
                __("legal.{$key}.description"),
                'legal.'.$key,
            ),
        ]);
    }
}
