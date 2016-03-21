<?php

namespace Yuloh\Ketch\Filters;

use function Yuloh\Neko\snake_case;

class SnakeCase
{
    public function __invoke($word)
    {
        return snake_case($word);
    }
}
