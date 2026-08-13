import './bootstrap';

document.addEventListener('click', (event) => {
    const target = event.target.closest('[data-track]');

    if (! target) {
        return;
    }

    const eventType = target.dataset.track;
    let meta = {};

    if (target.dataset.trackMeta) {
        try {
            meta = JSON.parse(target.dataset.trackMeta);
        } catch {
            meta = {};
        }
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    if (! token || ! eventType) {
        return;
    }

    const locale = document.documentElement.lang?.replace('-', '_') || undefined;

    fetch('/track', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            Accept: 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            event_type: eventType,
            path: window.location.pathname,
            locale,
            meta,
        }),
        keepalive: true,
    }).catch(() => {});

    trackExternal(eventType, meta);
});

function trackExternal(eventType, meta) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', eventType, meta);
    }

    const yandexId = document.querySelector('meta[name="analytics-yandex"]')?.content;

    if (yandexId && typeof window.ym === 'function') {
        window.ym(Number(yandexId), 'reachGoal', eventType, meta);
    }

    if (Array.isArray(window._hmt)) {
        window._hmt.push(['_trackEvent', 'promo', eventType, JSON.stringify(meta ?? {})]);
    }
}
