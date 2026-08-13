@php($members = $section['members'] ?? [])

<section id="team" class="section-shell relative overflow-hidden bg-sand-deep">
    <div class="pointer-events-none absolute inset-0 bg-pattern-cloud opacity-[0.10]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($members))
            <ul class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($members as $member)
                    <li class="surface-card">
                        @if (data_get($member, 'photo'))
                            <div class="window-round mb-4 h-16 w-16">
                                <img
                                    src="{{ $member['photo'] }}"
                                    alt=""
                                    class="h-full w-full rounded-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endif
                        <p class="font-semibold text-paper">{{ data_get($member, 'name', '') }}</p>
                        <p class="text-sm font-medium text-red">{{ data_get($member, 'role', '') }}</p>
                        @if (data_get($member, 'bio'))
                            <p class="mt-2 text-sm leading-relaxed text-paper/70">{{ $member['bio'] }}</p>
                        @endif
                        @if (data_get($member, 'linkedin'))
                            <a
                                href="{{ $member['linkedin'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-blue-bright transition hover:text-gold"
                                aria-label="{{ data_get($member, 'name', '').' — '.__('ui.linkedin') }}"
                            >
                                @include('sections._social-icon', ['key' => 'linkedin', 'class' => 'h-4 w-4'])
                                {{ __('ui.linkedin') }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
