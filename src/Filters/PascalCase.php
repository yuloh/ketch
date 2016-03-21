<?php

namespace Yuloh\Ketch\Filters;

use function Yuloh\Neko\pascal_case;

class PascalCase
{
    public function __invoke($word)
    {
        return pascal_case($word);
    }
}
