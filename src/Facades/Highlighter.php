<?php

namespace Dreitier\Documentation\Blade\Facades;

class Highlighter extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'highlighter';
    }

    /**
     * Return forward methods for custom Blade directives
     * @return \Closure[]
     */
    public static function getBladeDirectives()
    {
        return [
            'highlight' => function ($expression) {
                return implode('', [
                    '<?php echo Dreitier\Documentation\Blade\Facades\Highlighter::highlightFile(' . $expression . '); ?>'
                ]);
            },
            'highlightStyle' => function ($expression) {
                return implode('', [
                    '<link rel="stylesheet" href="<?php echo Dreitier\Documentation\Blade\Facades\Highlighter::createStyleUrl(' . $expression . '); ?>" />'
                ]);
            },
            'beginHighlight' => function ($expression) {
                return implode('', [
                    '<?php $language = ' . (empty($expression) ? 'null' : $expression) . '; ?>',
                    '<?php ob_start(); ?>'
                ]);
            },
            'endHighlight' => function ($expression) {
                return implode('', [
                    '<?php echo Dreitier\Documentation\Blade\Facades\Highlighter::highlight(ob_get_clean(), $language); ?>'
                ]);
            }
        ];
    }
}