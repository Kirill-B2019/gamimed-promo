<?php

namespace App\Providers;

use App\Models\AnalyticsEvent;
use App\Models\ContactMessage;
use App\Models\ContentSection;
use App\Models\Site;
use App\Models\SiteGroup;
use App\Models\User;
use App\Policies\AnalyticsEventPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\ContentSectionPolicy;
use App\Policies\SiteGroupPolicy;
use App\Policies\SitePolicy;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Site::class, SitePolicy::class);
        Gate::policy(SiteGroup::class, SiteGroupPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ContentSection::class, ContentSectionPolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
        Gate::policy(AnalyticsEvent::class, AnalyticsEventPolicy::class);

        RateLimiter::for('hub-api', function (Request $request) {
            $user = $request->user();
            $key = $user?->getAuthIdentifier() ?? $request->ip();

            return Limit::perMinute(120)->by('hub-api:'.$key);
        });
    }
}
