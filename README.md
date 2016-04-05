# Ketch

## Introduction

Ketch is a really simple way to start new projects.  Pick a template, answer a few questions, and your project is setup.  You don't need to copy from paste or start from scratch.

## How It Works

A ketch template is just a github repo of text files using mustache syntax.  Here's a simple PHP project template, with a `composer.json` and `README.md`:

```json
{
    "name": "{{ vendor }}/{{ package }}",
    "type": "library",
    "description": "{{ description }}",
    "license": "MIT",
    "require": {
        "php" : "~5.5|~7.0"
    },
    "require-dev": {
        "phpunit/phpunit" : "4.*"
    },
    "autoload": {
        "psr-4": {
            "{{ vendor | case.pascal }}\\{{ package | case.pascal }}\\": "src"
        }
    }
}
```

```markdown
# {{ package | case.upper }}

{{ description }}

## Install

    $ composer require {{ vendor }}/{{ package }}
```

Ketch will ask you to fill in the blanks:

```bash
ketch create yuloh/skeleton my-project
vendor: yuloh
package: my-project
description: An awesome project created with ketch.
Project ready!
```

...and your new `my-project` directory is created with the answers filled in, and you are ready to go.

```json
{
    "name": "yuloh/my-project",
    "type": "library",
    "description": "An awesome project created with ketch.",
    "license": "MIT",
    "require": {
        "php" : "~5.5|~7.0"
    },
    "require-dev": {
        "phpunit/phpunit" : "4.*"
    },
    "autoload": {
        "psr-4": {
            "Yuloh\\MyProject\\": "src"
        }
    }
}
```

```markdown
# My-project

An awesome project created with ketch.

## Install

    $ composer require yuloh/my-project
```

## Install

Ketch is installed using [composer](https://getcomposer.org/).  You need to install ketch globally so that you can use it in any directory.

```
composer global require yuloh/ketch:dev-master
```

## Usage

### Creating Projects

To create a new project invoke ketch like this: `ketch create <template> <name>`.  Here's an example, creating the new project  "my-project" with the template "yuloh/skeleton".  The project will be created in the "my-project" folder in the current directory.

```
ketch create yuloh/skeleton my-project
```

### Creating Templates

A template is just a github repository with mustache templates in the `template` directory.  If you want to see a simple template check out the [yuloh/skeleton](https://github.com/yuloh/skeleton) template.

#### Filters

Ketch ships with a few common filters.  The default filters are `case.upper`, `case.snake`, `case.pascal`, `case.camel`, and `case.kebab`.  You can use a filter with a pipe like this: `{{ variable | case.upper }}`.

#### Boolean values

You can use boolean values too.  Input values of 'y/n', 'yes/no', or 'true/false' it will be cast to true/false.  Ketch doesn't know if you are using booleans so it's up to you to let the user know (i.e. include `[y/n]` in the variable name).  Usage looks like this:

```
"require-dev": {
    {{#include behat [yes]?}}
    "behat/behat": "~3.1.0rc2"
    {{/include behat [yes]?}}
}
```

### Library Usage

Ketch can also be used as a PHP library.  This is helpful if you want to customize the scaffolding process.  Usage is like this:

```php
$src  = __DIR__ . '/my-template';
$dest = __DIR__ . 'my-project';

$ketch = new Yuloh\Ketch\Ketch();
$ketch->generate($src, $dest);
```

You can also register your own filters.  Check out the `Yuloh\Ketch\Ketch` class for more info.

## Testing

If you are commiting changes to ketch, you can run the tests like this:

```bash
vendor/bin/behat
```

## Credits

Ketch was inspired by [khaos](http://khaos.io/) for node.  Built by [yuloh](https://twitter.com/__yuloh).
