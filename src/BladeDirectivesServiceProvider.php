<?php

namespace Dreitier\Documentation\Blade;

use Dreitier\Documentation\Blade\Content\Locator;
use Dreitier\Documentation\Blade\Diff\Differ;
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
        App::bind('staticContentLocator', function () {
            return new Locator();
        });

        App::bind('highlighter', function () {
            $r = new Highlighter();
            return $r;
        });

        App::bind('differ', function () {
            $r = new Differ();
            return $r;
        });

        $directives = array_merge(
            \Dreitier\Documentation\Blade\Facades\Highlighter::getBladeDirectives(),
            \Dreitier\Documentation\Blade\Facades\Differ::getBladeDirectives(),
        );

        // register highlighter directives
        foreach ($directives as $key => $callback) {
            Blade::directive($key, $callback);
        };
    }
}