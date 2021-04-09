<?php

namespace Dreitier\Documentation\Blade\Facades;

class StaticContentLocator extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'staticContentLocator';
    }
}