<?php

namespace Yuloh\Ketch\Filters;

use Doctrine\Common\Inflector\Inflector;

class SnakeCase
{
    public function __invoke($word)
    {
        return Inflector::tableize($word);
    }
}
