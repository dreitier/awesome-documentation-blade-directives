<?php

namespace Dreitier\Documentation\Blade;

use Dreitier\Documentation\Blade\Content\Locator;
use Dreitier\Documentation\Blade\Facades\StaticContentLocator;
use Dreitier\Documentation\Blade\Highlight\Highlighter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeDirectivesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerDirectives();
    }

    /**
     * Register all directives.
     *
     * @return void
     */
    public function registerDirectives()
    {
        // $directives = require __DIR__ . '/directives.php';

        App::bind('staticContentLocator', function () {
            return new Locator();
        });

        App::bind('highlighter', function () {
            $r = new Highlighter();
            return $r;
        });

        $directives = \Dreitier\Documentation\Blade\Facades\Highlighter::getBladeDirectives();

        // register highlighter directives
        foreach ($directives as $key => $callback) {
            Blade::directive($key, $callback);
        };
    }
}