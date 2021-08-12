<?php

namespace Georgeboot\LaravelTiptap;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/larvel-tiptap.php',
            'larvel-tiptap'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/larvel-tiptap.php' => config_path('larvel-tiptap.php'),
        ], 'larvel-tiptap-config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-tiptap');

        Blade::component('blade-icons::components.tiptap-editor', 'tiptap-editor');
    }
}
