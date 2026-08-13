@php($members = $section['members'] ?? [])

<section id="team" class="section-shell bg-sand-deep">
    <div class="section-inner">
        @include('sections._header', ['section' => $section])

        @if (count($members))
            <ul class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($members as $member)
                    <li class="luxury-card">
                        @if (data_get($member, 'photo'))
                            <img
                                src="{{ $member['photo'] }}"
                                alt=""
                                class="mb-4 h-16 w-16 rounded-full object-cover"
                                loading="lazy"
                            >
                        @endif
                        <p class="font-semibold text-ink">{{ data_get($member, 'name', '') }}</p>
                        <p class="text-sm font-medium text-emerald">{{ data_get($member, 'role', '') }}</p>
                        @if (data_get($member, 'bio'))
                            <p class="mt-2 text-sm leading-relaxed text-ink/70">{{ $member['bio'] }}</p>
                        @endif
                        @if (data_get($member, 'linkedin'))
                            <a
                                href="{{ $member['linkedin'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-emerald transition hover:text-emerald-bright"
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
