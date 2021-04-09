<?php

namespace Dreitier\Documentation\Blade\Facades;

class Differ extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'differ';
    }

    /**
     * Return forward methods for custom Blade directives
     * @return \Closure[]
     */
    public static function getBladeDirectives()
    {
        return [
            'differStyle' => function ($expression) {
                return implode('', [
                    '<style type="text/css"><?php echo Dreitier\Documentation\Blade\Facades\Differ::createInlineCss(' . $expression . '); ?></style>'
                ]);
            },
            'diffArray' => function ($expression) {
                return implode('', [
                    '<?php echo Dreitier\Documentation\Blade\Facades\Differ::renderDiffArray(' . $expression . '); ?>'
                ]);
            },
            'diffEncodedJson' => function ($expression) {
                return implode('', [
                    '<?php echo Dreitier\Documentation\Blade\Facades\Differ::renderDiffFromEncodedJson(' . $expression . '); ?>'
                ]);
            }
        ];
    }
}