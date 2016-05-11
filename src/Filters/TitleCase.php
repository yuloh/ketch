<?php

namespace Yuloh\Ketch\Filters;

class TitleCase
{
    public function __invoke($word)
    {
        return ucwords($word);
    }
}
