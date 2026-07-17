<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Inertia のルート Blade テンプレート。
     */
    protected $rootView = 'app';

    /**
     * すべての Inertia レスポンスで共有する props。
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            // フロント(app.jsx)から測定IDを参照できるように共有しておく
            'ga4Id' => config('services.ga4.id'),
        ];
    }
}
