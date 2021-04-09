<?php

namespace Dreitier\Documentation\Blade\Diff;

use Dreitier\Documentation\Blade\Facades\StaticContentLocator;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;

class Differ
{
    /**
     * Default renderer
     * @var string
     */
    private $defaultRenderer = 'Inline';

    /**
     * Default renderer options
     *
     * @var string[]
     */
    private $defaultRendererOptions = array(
        'detailLevel' => 'line',
        'language' => 'eng');

    /**
     * Default differ options
     *
     * @var array
     */
    private $defaultDifferOptions = array('context' => 3,
        'ignoreCase' => true,
        'ignoreWhitespace' => true);

    /**
     * Default CSS; if this is empty, a fallback to php-diff's default stylesheet will happen
     * @var null
     */
    private $defaultCss = null;

    /**
     * Based upon the parameters, the target inline CSS is calculated
     * @param null $css
     * @return mixed|string|null
     */
    private function interpolateCss($css = null)
    {
        $r = $this->defaultCss;

        if (!empty($css)) {
            $r = $css;
        }

        if (empty($r)) {
            $r = \Jfcherng\Diff\DiffHelper::getStyleSheet();
        }

        return $r;
    }

    /**
     * Get default renderer
     * @return string
     */
    public function getDefaultRenderer()
    {
        return $this->defaultRenderer;
    }

    /**
     * Set default renderer
     * @param $defaultRenderer
     */
    public function setDefaultRenderer($defaultRenderer)
    {
        $this->defaultRenderer = $defaultRenderer;
    }

    /**
     * Get default renderer options
     * @return string[]
     */
    public function getDefaultRendererOptions()
    {
        return $this->defaultRendererOptions;
    }

    /**
     * Set default renderer options
     * @param array $defaultRendererOptions
     */
    public function setDefaultRendererOptions(array $defaultRendererOptions)
    {
        $this->defaultRendererOptions = $defaultRendererOptions;
    }

    /**
     * Get default differ options
     * @return array
     */
    public function getDefaultDifferOptions()
    {
        return $this->defaultDifferOptions;
    }

    /**
     * Set default differ options
     * @param array $differOptions
     */
    public function setDefaultDifferOptions(array $differOptions)
    {
        $this->defaultDifferOptions = $differOptions;
    }

    /**
     * Get default CSS
     * @return null
     */
    public function getDefaultCss()
    {
        return $this->defaultCss;
    }

    /**
     * Set default CSS
     * @param $css
     */
    public function setDefaultCss($css)
    {
        $this->defaultCss = $css;
    }

    /**
     * @param null $css This has priority; if this is empty, a fallback to $this->defaultCss happens and then php-diff's default CSS is used as last chance.
     * @return mixed|string|null
     */
    public function createInlineCss($css = null)
    {
        return $this->interpolateCss($css);
    }

    /**
     * Either user the provided renderer or fallback to the defaultRenderer
     * @param $renderer
     * @return mixed|string
     */
    private function interpolateRenderer($renderer)
    {
        return !empty($renderer) ? $renderer : $this->defaultRenderer;
    }

    /**
     * Create a result array either by combining both or favoring the custom option
     * @param $defaultOptions
     * @param null $customOptions
     * @param false $doMerge
     * @return array|mixed
     */
    private function interpolateWithMerge($defaultOptions, $customOptions = null, $doMerge = false)
    {
        $r = $defaultOptions;

        if (!empty($customOptions)) {
            if ($doMerge) {
                $r = array_merge_recursive($defaultOptions, $customOptions);
            } else {
                $r = $customOptions;
            }
        }

        return $r;

    }

    private function interpolateDifferOptions($differOptions = null, $doMerge = false)
    {
        return $this->interpolateWithMerge($this->defaultDifferOptions, $differOptions, $doMerge);
    }

    private function interpolateRendererOptions($rendererOptions = null, $doMerge = false)
    {
        return $this->interpolateWithMerge($this->defaultRendererOptions, $rendererOptions, $doMerge);
    }

    /**
     * Do a rendering of the given array
     * @param $array
     * @param null $renderer
     * @param null $rendererOptions
     * @param false $mergeOptions
     * @return string
     * @throws \Jfcherng\Diff\Exception\UnsupportedFunctionException
     */
    public function renderDiffArray(array $array, $renderer = null, $rendererOptions = null, $mergeOptions = false)
    {
        $renderer = RendererFactory::make(
            $this->interpolateRenderer($renderer),
            $this->interpolateRendererOptions($rendererOptions, $mergeOptions)
        );

        return $renderer->renderArray($array);
    }

    /**
     * Decode the previous calculated JSON diff and render the result
     *
     * @param $json
     * @param null $renderer
     * @param null $rendererOptions
     * @param false $mergeOptions
     * @return string
     * @throws \Jfcherng\Diff\Exception\UnsupportedFunctionException
     */
    public function renderDiffFromEncodedJson($json, $renderer = null, $rendererOptions = null, $mergeOptions = false)
    {
        return $this->renderDiffArray(json_decode($json, true), $renderer, $rendererOptions, $mergeOptions);
    }

    /**
     * Calculate a difference between two strings
     *
     * @param $oldContent
     * @param $newContent
     * @param string $renderer The "Json" renderer is used for this as default
     * @param null $differOptions
     * @param false $mergeDifferOptions
     * @param null $rendererOptions
     * @param false $mergeRendererOptions
     * @return string
     */
    public function calculate($oldContent, $newContent, $renderer = "Json", $differOptions = null, $mergeDifferOptions = false, $rendererOptions = null, $mergeRendererOptions = false)
    {
        return DiffHelper::calculate($oldContent, $newContent,
            $this->interpolateRenderer($renderer),
            $this->interpolateDifferOptions($differOptions, $mergeDifferOptions),
            $this->interpolateRendererOptions($rendererOptions, $mergeRendererOptions)
        );
    }

    /**
     * Calculate a difference between two files
     *
     * @param $oldFile You can use prefixes, registered in the StaticContentLocator
     * @param $newFile You can use prefixes, registered in the StaticContentLocator
     * @param string $renderer
     * @param null $differOptions
     * @param false $mergeDifferOptions
     * @param null $rendererOptions
     * @param false $mergeRendererOptions
     * @return string
     */
    public function calculateFiles($oldFile, $newFile, $renderer = "Json", $differOptions = null, $mergeDifferOptions = false, $rendererOptions = null, $mergeRendererOptions = false)
    {
        return $this->calculate(
            StaticContentLocator::locate($oldFile),
            StaticContentLocator::locate($newFile),
            $renderer,
            $differOptions,
            $mergeDifferOptions,
            $rendererOptions,
            $mergeRendererOptions
        );
    }
}