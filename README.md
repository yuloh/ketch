# Ketch

## Introduction

Ketch is a really simple way to start new projects.  Pick a template, answer a few questions, and your project is setup.  You don't need to copy & paste or start from scratch.

Since Ketch is written in PHP, you don't need to use Yeoman to setup your PHP projects anymore.

## How It Works

A ketch template is just a github repo full of text files using mustache syntax.  Ketch will download the repo, ask you to fill in the blanks, and build your project.

## Install

Ketch is installed using [composer](https://getcomposer.org/).  You need to install ketch globally so that you can use it in any directory.

```
composer global require yuloh/ketch
```

## Usage

### Creating Projects

To create a new project invoke ketch like this: `ketch create <template> <name>`.  Here's an example, creating the new project  "my-project" with the template that comes with this repo.  The project will be created in the "my-project" folder in the current directory.

```
ketch create yuloh/ketch my-project
```

If you are actually using the default template, you can just omit the template argument entirely.

```
ketch create my-project
```

### Creating Templates

A template is just a github repository with mustache templates in the `template` directory.  If you want to see a simple template check out the [default Ketch](./template) template.

#### Filters

Ketch ships with a few common filters.  The default filters are `case.upper`, `case.title`, `case.snake`, `case.pascal`, `case.camel`, and `case.kebab`.  You can use a filter with a pipe like this: `{{ variable | case.upper }}`.

#### Boolean values

You can use boolean values too.  Input values of 'y/n', 'yes/no', or 'true/false' it will be cast to true/false.  Ketch doesn't know if you are using booleans so it's up to you to let the user know (i.e. include `[y/n]` in the variable name).  Usage looks like this:

```
"require-dev": {
    {{#include behat [yes]?}}
    "behat/behat": "~3.1.0rc2"
    {{/include behat [yes]?}}
}
```

#### Using .gitattributes

Since Ketch downloads the release tar for templates, having a `.gitattributes` file would prevent ketch from being able to download any of the files listed in it.

To get around this limitation you can name your file `.gitattributes.dist`.  Ketch will rename it to `.gitattributes` once it's downloaded.

#### Comments

Since ketch uses mustache syntax, you can use mustache comments in your template.

```
{{!  This is a comment that won't show up in the generated file }}
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
