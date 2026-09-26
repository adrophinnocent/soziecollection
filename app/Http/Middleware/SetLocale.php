<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * The admin portal is English only, so a storefront Swahili session must never
     * change the language advertised for back office pages.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($this->resolveLocale($request));

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        $default = (string) config('app.locale', 'en');

        if ($request->is('admin', 'admin/*')) {
            return $default;
        }

        /** @var array<int, string> $availableLocales */
        $availableLocales = config('app.available_locales', ['en', 'sw']);
        $locale = session('locale');

        if (is_string($locale) && in_array($locale, $availableLocales, true)) {
            return $locale;
        }

        return $default;
    }
}
