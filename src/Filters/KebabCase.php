<?php

namespace Yuloh\Ketch\Filters;

use function Yuloh\Neko\kebab_case;

class KebabCase
{
    public function __invoke($word)
    {
        return kebab_case($word);
    }
}
