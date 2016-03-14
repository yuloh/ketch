<?php

namespace Yuloh\Ketch\Filters;

class KebabCase
{
    public function __invoke($word)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '-$1', str_replace('_', '-', $word)));
    }
}
