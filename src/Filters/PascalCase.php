<?php

namespace Yuloh\Ketch\Filters;

use Doctrine\Common\Inflector\Inflector;

class PascalCase
{
    public function __invoke($word)
    {
        return Inflector::classify($word);
    }
}
