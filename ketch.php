<?php

if (file_exists(__DIR__ . '/../../autoload.php')) {
    require __DIR__ . '/../../autoload.php';
} else {
    require __DIR__ . '/vendor/autoload.php';
}

use Yuloh\Ketch\Commands;

$app = new Silly\Application('Ketch', '0.1.0');

$app
    ->command('create name [template]', new Commands\Create())
    ->defaults(['template' => 'yuloh/ketch'])
    ->descriptions('Create a new project', [
        'name'     => 'The project name, i.e. "my-project"',
        'template' => 'The Template\'s Github repository, i.e. "yuloh/ketch"',
    ]);

$app->run();
