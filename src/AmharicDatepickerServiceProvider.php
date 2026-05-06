<?php

namespace Yafet\AmharicDatepicker;

use Yafet\AmharicDatepicker\View\Components\AmharicDatepicker;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AmharicDatepickerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/amharic-datepicker.php', 'amharic-datepicker');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'amharic-datepicker');

        Blade::component('amharic-datepicker', AmharicDatepicker::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/amharic-datepicker.php' => config_path('amharic-datepicker.php'),
            ], 'amharic-datepicker-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/amharic-datepicker'),
            ], 'amharic-datepicker-views');

            $this->publishes([
                __DIR__ . '/../resources/js' => public_path('vendor/amharic-datepicker/js'),
                __DIR__ . '/../resources/css' => public_path('vendor/amharic-datepicker/css'),
            ], 'amharic-datepicker-assets');
        }
    }
}
