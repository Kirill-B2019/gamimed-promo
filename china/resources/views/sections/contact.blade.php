@php
    $wechat = data_get($settings, 'wechat.url', config('site.settings.wechat.url'));
    $wechatQr = data_get($settings, 'wechat.qr', config('site.settings.wechat.qr'));
    $wechatId = data_get($settings, 'wechat.id', config('site.settings.wechat.id'));
@endphp

<section id="contact" class="section-shell relative overflow-hidden bg-sand-deep">
    <div class="pointer-events-none absolute inset-0 bg-pattern-dragon opacity-[0.08]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(16rem,20rem)_minmax(0,1fr)] lg:items-start">
            @if ($wechat || $wechatQr || $wechatId)
                <aside class="surface-card border-red/20 p-6" aria-label="{{ __('ui.wechat_cta') }}">
                    <p class="text-sm font-semibold uppercase tracking-wide text-red">{{ __('ui.wechat_cta') }}</p>
                    <p class="mt-2 text-sm text-paper/70">{{ __('ui.wechat_scan') }}</p>

                    <div class="mt-4 flex flex-col items-start gap-4">
                        <div class="window-round h-40 w-40">
                            <div class="flex h-full w-full items-center justify-center rounded-full bg-paper p-3">
                                @if ($wechatQr)
                                    <img
                                        src="{{ $wechatQr }}"
                                        alt="{{ __('ui.wechat_qr') }}"
                                        class="h-full w-full object-contain"
                                        width="160"
                                        height="160"
                                    >
                                @else
                                    <img
                                        src="/images/wechat-qr.svg"
                                        alt="{{ __('ui.wechat_qr') }}"
                                        class="h-full w-full object-contain"
                                        width="160"
                                        height="160"
                                    >
                                @endif
                            </div>
                        </div>

                        @if ($wechatId)
                            <p class="text-sm text-paper">
                                <span class="text-paper/60">{{ __('ui.wechat_id_label') }}:</span>
                                <span class="ms-1 font-semibold">{{ $wechatId }}</span>
                            </p>
                        @endif

                        @if ($wechat)
                            <a
                                href="{{ $wechat }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="cta-luck"
                                data-track="cta_click"
                                data-track-meta='{"target":"wechat"}'
                            >
                                <svg class="me-2 h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M9.5 4C5.36 4 2 6.91 2 10.5c0 2.05 1.12 3.88 2.86 5.08L4.2 18.3a.5.5 0 0 0 .64.66l3.16-1.42c.48.08.98.13 1.5.13.27 0 .54-.02.8-.04C10.02 16.7 10 15.86 10 15.5c0-3.59 3.36-6.5 7.5-6.5.22 0 .44.01.66.03C17.4 6.05 13.8 4 9.5 4m-3.25 3.25a1.12 1.12 0 1 1 0 2.25 1.12 1.12 0 0 1 0-2.25m5.5 0a1.12 1.12 0 1 1 0 2.25 1.12 1.12 0 0 1 0-2.25M17.5 10c-3.59 0-6.5 2.46-6.5 5.5S13.91 21 17.5 21c.52 0 1.02-.05 1.5-.13l2.3 1.03a.4.4 0 0 0 .52-.53l-.48-1.72C22.14 18.7 23 17.2 23 15.5 23 12.46 21.09 10 17.5 10m-2.12 3.12a.88.88 0 1 1 0 1.76.88.88 0 0 1 0-1.76m4.24 0a.88.88 0 1 1 0 1.76.88.88 0 0 1 0-1.76"/>
                                </svg>
                                {{ __('ui.wechat_cta') }}
                            </a>
                        @endif
                    </div>
                </aside>
            @endif

            <div class="surface-card max-w-xl p-6">
                <livewire:contact-form />
            </div>
        </div>
    </div>
</section>
