<?php

namespace Dreitier\Documentation\Blade\Highlight;

use Dreitier\Documentation\Blade\Facades\StaticContentLocator;

class Highlighter
{
    private $defaultLanguage = 'php';
    private $highlighter = null;
    private $highlightJsVersion = "9.12.0";
    private $highlightJsStyle = "atelier-heath-dark";

    /**
     * Get a Highlighter instance
     * @return \Highlight\Highlighter
     */
    public function getHighlighter()
    {
        if ($this->highlighter == null) {
            $this->highlighter = new \Highlight\Highlighter();
        }

        return $this->highlighter;
    }

    /**
     * Set the highlight.js version
     * @param $version
     */
    public function setHighlightJsVersion($version)
    {
        $this->highlightJsVersion = $version;
    }

    /**
     * Set the highlight.js style
     * @param $style
     */
    public function setHighlightJsStyle($style)
    {
        $this->highlightJsStyle = $style;
    }

    /**
     * Set default language for highlight js, which is 'php' by default
     * @param $language
     */
    public function setDefaultLanguage($language)
    {
        $this->defaultLanguage = $language;
    }

    /**
     * Based upon the highlight.js version and style, the <link rel="stylesheet" href="*" /> attribute is constructed.
     * @param null $style
     * @param null $version
     * @return string
     */
    public function createStyleUrl($style = null, $version = null)
    {
        return implode('', [
            "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/",
            (!empty($version) ? $version : $this->highlightJsVersion),
            "/styles/",
            (!empty($style) ? $style : $this->highlightJsStyle),
            ".min.css"]);
    }

    /**
     * Either use the provided language or the default language
     * @param null $usedLanguage
     * @return mixed|string
     */
    private function interpolateLanguage($usedLanguage = null)
    {
        return !empty($usedLanguage) ? $usedLanguage : $this->defaultLanguage;
    }

    /**
     * Highglight the given content. <pre /> and <code /> tags are automatically added.
     * @param $content
     * @param null $language
     * @return string
     * @throws \Exception
     */
    public function highlight($content, $language = null)
    {
        $language = $this->interpolateLanguage($language);

        $code = $this->getHighlighter()->highlight($language, $content);
        return "<pre><code='highlightjs $language'>" . $code->value . "</code></pre>";
    }

    /**
     * The content of a file is included.
     * @param $path You can use any path format which is supported by the Locator instance
     * @param null $language
     * @return string
     * @throws \Exception
     */
    public function highlightFile($path, $language = null)
    {
        $content = StaticContentLocator::locate($path);

        if ($content === null) {
            return "Missing static content '$path'";
        }

        return $this->highlight($content, $language);
    }
}