<?php

namespace Yuloh\Ketch\Filters;

class UpperCase
{
    public function __invoke($word)
    {
        return ucfirst($word);
    }
}
