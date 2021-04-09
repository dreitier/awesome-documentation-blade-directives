<?php

namespace Dreitier\Documentation\Blade\Content;

class Locator
{
    private $locators = [];

    public function __construct()
    {
        $this->registerDefaultLocators();;
    }

    /**
     * Register default locators
     */
    private function registerDefaultLocators()
    {
        $this->locators['view'] = function ($path) {
            return resource_path('views') . '/' . $path;
        };
    }

    public function locate($path)
    {
        $data = explode(":", $path);

        if (sizeof($data) >= 2) {
            if (array_key_exists($data[0], $this->locators)) {
                $method = $this->locators[$data[0]];
                $path = $method($data[1]);
            }
        }

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return null;
    }

    public function register($prefix, callable $method)
    {
        $this->locators[$prefix] = $method;
    }
}