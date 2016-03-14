<?php

namespace Yuloh\Ketch\Filters;

use Doctrine\Common\Inflector\Inflector;

class CamelCase
{
    public function __invoke($word)
    {
        return Inflector::camelize($word);
    }
}
