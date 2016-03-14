<?php

namespace Yuloh\Ketch;

use Mustache_Exception_UnknownTemplateException;

class FileLoader implements \Mustache_Loader
{
    /**
     * @var \SplFileInfo[]
     */
    private $files;

    /**
     * @param \SplFileInfo[] $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * Load a Template by name.
     *
     * @throws Mustache_Exception_UnknownTemplateException If a template file is not found.
     *
     * @param string $name
     *
     * @return string Mustache Template source
     */
    public function load($name)
    {
        if (!isset($this->files[$name])) {
            throw new Mustache_Exception_UnknownTemplateException($name);
        }

        return file_get_contents($this->files[$name]->getPathName());
    }
}
