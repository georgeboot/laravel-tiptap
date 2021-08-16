<?php

namespace Georgeboot\LaravelTiptap;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-tiptap.php',
            'laravel-tiptap'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-tiptap.php' => config_path('laravel-tiptap.php'),
        ], 'laravel-tiptap-config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-tiptap');

        Blade::component('laravel-tiptap::components.tiptap-editor', 'tiptap-editor');
    }
}
