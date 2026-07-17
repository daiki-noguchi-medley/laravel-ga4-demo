// パターンA/B(Blade 主体)用のエントリ。
// ここでは Alpine を起動するだけ。GA4 の初期化は Blade 側の partials/ga4.blade.php が担当する。
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
