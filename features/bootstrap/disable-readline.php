<?php

/**
 * This stub lets us disable readline for tests,
 * since it doesn't work as well with expect.
 */

namespace Yuloh\Ketch {
    function function_exists($name)
    {
        if ($name === 'readline') {
            return false;
        }

        return \function_exists($name);
    }
}
