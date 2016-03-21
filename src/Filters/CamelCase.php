<?php

namespace Yuloh\Ketch\Filters;

use function Yuloh\Neko\camel_case;

class CamelCase
{
    public function __invoke($word)
    {
        return camel_case($word);
    }
}
