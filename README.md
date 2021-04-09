# Blade Directives for Awesome Documentation

If you want to create awesome documentation for developers, this package is for you.

## Installation

You can install the package via composer:

```bash
composer require dreitier/awesome-documentation-blade-directives
```

## Usage

### Highlighting source code

This package uses [scrivo/highlight.php](https://github.com/scrivo/highlight.php) to create highlighted source code.

#### @highlightStyle

Creates a `<link rel="stylsheet" />` with the given style and version if provided.

```blade
@highlightStyle("atelier-heath-dark" /* style, optional */, "9.12.0" /* version, optional */)
```

#### @highlight

Highlight a given source code file.

- `$path` can be something like 'view:$path_in_laravel_views_folder' or a custom prefixed path
- `$language` can be any supported language by highlight.js

```blade
@highlight('view:documentation/samples/flow.php' /* path */, 'php' /* language, optional */)
```

#### @beginHighlight / @endHighlight

Highlight inline source code

```blade
@beginHighlight('php' /* language, optional */)
$a = 1;
$b = 2;
echo "$a + $b = " . ($a + $b);
@endHighlight
```

### Diffing files

This package uses [jfcherng/php-diff](https://github.com/jfcherng/php-diff) to create diffs between two strings.

#### @differStyle

Create inline CSS style to apply for the diff result.

```blade
@differStyle
```

#### @diffEncodedJson

Renders a previously created JSON diff:

```php
$jsonDiff = \Dreitier\Documentation\Blade\Facades\Differ::calculate('old', 'new', 'Json' /* default, optional */);
```

```blade
@diffEncodedJson($jsonDiff, $render = null, $rendererOptions = null, $mergeOptions = false)
```

#### @diffArray

Renders a previously created array diff:

```blade
@diffArray($diff, $render = null, $rendererOptions = null, $mergeOptions = false)
```

#### Create a diff

You can easily create a diff between two files by using the facade:

```php
$encodedJson = \Dreitier\Documentation\Blade\Facades\Differ::calculateFiles('view:samples/v1.php', 'view:samples/v2.php', $renderer = 'Json', $differOptions = null, $mergeDifferOptions = false, $rendererOptions = null, $mergeRendererOptions = false)
```

As you can see, you can use the prefix format of the static content locator (see below) to easily reference files.

## Configuration

#### Highlighting source code

##### Setting highlight.js options

You can configure the used highlight.js options by using the facade:

```php
\Dreitier\Documentation\Blade\Facades\Highlighter::setHighlightJsVersion('9.12.1');
\Dreitier\Documentation\Blade\Facades\Highlighter::setHighlightJsStyle('atelier-heath-light');
```

##### Set default language

If you want to set another default language than PHP, you can use `setDefaultLanguage`:

```php
\Dreitier\Documentation\Blade\Facades\Highlighter::setDefaultLanguage('java');
```

#### Diffing files

You can use the following methods to configure the default options
of [jfcherng/php-diff](https://github.com/jfcherng/php-diff):

```php
\Dreitier\Documentation\Blade\Facades\Differ::setDefaultRenderer($defaultRenderer);
\Dreitier\Documentation\Blade\Facades\Differ::setDefaultRendererOptions($defaultRendererOptions);
\Dreitier\Documentation\Blade\Facades\Differ::setDefaultDifferOptions($differOptions);
\Dreitier\Documentation\Blade\Facades\Differ::setDefaultCss($css);
```

#### Static content locator

##### Register a new static content locator

This one comes handy if you want to reference source code or samples from other places, like composer packages:

```php
\Dreitier\Documentation\Blade\Facades\StaticContentLocator::register('my-composer-package', function($path) {
    return base_path('vendor/my-namespace/my-composer-package/samples') . '/' . $path;
});
```

```blade
@highlight('my-composer-package:subdir/sample_1.json', 'json')
```