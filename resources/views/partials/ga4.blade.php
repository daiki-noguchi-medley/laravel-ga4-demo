{{--
    GA4 共通タグ(全パターン共通の土台)
    - 測定ID(config services.ga4.id)が設定されているときだけ出力
    - $spa = true  … SPA(Inertia)向け。自動 page_view を止め、遷移時に手動送信する
    - $spa = false … Blade主体(MPA)向け。物理ロードごとに page_view 自動送信
    - 本番以外では debug_mode を付与 → GA4 の DebugView でローカル検証できる
--}}
@php($ga4Id = config('services.ga4.id'))
@if ($ga4Id)
    @php($spa = $spa ?? false)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', @json($ga4Id), {
            // SPA のときは自動送信を止め、JS 側の遷移イベントで送る
            send_page_view: @json(! $spa),
            // 本番以外は DebugView に流す
            debug_mode: @json(! app()->isProduction()),
        });
    </script>
@else
    {{-- 測定ID未設定。GA4_MEASUREMENT_ID を .env / docker-compose に設定してください --}}
    <!-- GA4: GA4_MEASUREMENT_ID is not set -->
@endif
