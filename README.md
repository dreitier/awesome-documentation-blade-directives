# Blade Directives for Awesome Documentation

If you want to create awesome documentation for developers, this package is for you.

## Installation

You can install the package via composer:

```bash
composer require dreitier/awesome-documentation-blade-directives
```

## Usage

### Highlighting source code

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

## Configuration

#### Highlighting source code

##### Setting highlight.js options

You can configure the used highlight.js options by using the facade:

```php
Dreitier\Documentation\Blade\Facades\Highlighter::setHighlightJsVersion('9.12.1');
Dreitier\Documentation\Blade\Facades\Highlighter::setHighlightJsStyle('atelier-heath-light');
```

##### Set default language

If you want to set another default language than PHP, you can use `setDefaultLanguage`:

```php
Dreitier\Documentation\Blade\Facades\Highlighter::setDefaultLanguage('java');
```

#### Static content locator

##### Register a new static content locator

This one comes handy if you wnat to reference source code or samples from other places, like composer packages:

```php
\Dreitier\Documentation\Blade\Facades\StaticContentLocator::register('my-composer-package', function($path) {
    return base_path('vendor/my-namespace/my-composer-package/samples') . '/' . $path;
});
```

```blade
@highlight('my-composer-package:subdir/sample_1.json', 'json')
```