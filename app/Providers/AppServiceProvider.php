<?php

namespace App\Providers;

use App\Services\GeminiImageGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GeminiImageGenerator::class, fn () => new GeminiImageGenerator(
            config('services.gemini.key'),
            config('services.gemini.model'),
            config('services.gemini.base_url'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
