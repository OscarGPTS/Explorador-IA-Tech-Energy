<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS cuando el request viene por un proxy TLS (Cloudflare /
        // Cloudflare Zero Trust). Esto asegura que route(), url() y asset()
        // generen URLs con https:// aunque internamente nginx/php reciba http://.
        $request = request();
        $forwardedProto = $request->server('HTTP_X_FORWARDED_PROTO');

        if ($request->isSecure()
            || $forwardedProto === 'https'
            || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
