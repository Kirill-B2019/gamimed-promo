@php
    $ga4 = config('site.analytics.ga4');
    $yandex = config('site.analytics.yandex');
    $baidu = config('site.analytics.baidu');
@endphp
@if ($ga4)
    <meta name="analytics-ga4" content="{{ $ga4 }}">
@endif
@if ($yandex)
    <meta name="analytics-yandex" content="{{ $yandex }}">
@endif
@if ($baidu)
    <meta name="analytics-baidu" content="{{ $baidu }}">
@endif

@if ($ga4)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4 }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($ga4));
    </script>
@endif

@if ($yandex)
    <script>
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(@json((int) $yandex), "init", {clickmap:true, accurateTrackBounce:true, trackLinks:true});
    </script>
@endif

@if ($baidu)
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?" + @json($baidu);
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
@endif
