<?php

namespace Yuloh\Ketch;

use FilesystemIterator;
use Mustache_Engine;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Yuloh\Ketch\Filters;

class Ketch
{
    /**
     * @var array
     */
    private $helpers;

    /**
     * @param array $helpers
     */
    public function __construct(array $helpers = [])
    {
        $this->helpers = $helpers ?: $this->getDefaultHelpers();
    }

    /**
     * @param string $src  The source directory.
     * @param string $dest The destination directory.
     */
    public function generate($src, $dest)
    {
        $files = $this->allFiles($src);

        $mustache = new Mustache_Engine([
            'loader'  => new FileLoader($files),
            'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
            'helpers' => $this->helpers
        ]);

        $blacklist = array_keys($this->helpers);
        $context   = new AskContext($blacklist);

        $this->createDestinationDirectory($dest);

        foreach ($files as $name => $file) {

            $rendered = $mustache
                ->loadTemplate($name)
                ->render($context);

            $outPathName = $this->getDestinationPathName($src, $dest, $name);
            $this
                ->createAnyIntermediateDirectories($outPathName)
                ->writeRenderedTemplate($outPathName, $rendered);
        }
    }

    /**
     * Register a helper with Ketch.  The helper will be passed directly to Mustache.
     *
     * @param string $name
     * @param mixed  $helper
     */
    public function registerHelper($name, $helper)
    {
        $this->helpers[$name] = $helper;
    }

    /**
     * Get the default helpers used by Ketch.
     *
     * @return array
     */
    private function getDefaultHelpers()
    {
        return [
            'case' => [
                'upper'  => new Filters\UpperCase(),
                'snake'  => new Filters\SnakeCase(),
                'pascal' => new Filters\PascalCase(),
                'camel'  => new Filters\CamelCase(),
                'kebab'  => new Filters\KebabCase(),
            ]
        ];
    }

    /**
     * Return an array of files in the given path.  The array is indexed
     * by file name.
     *
     * @param string $path
     * @return \SplFileInfo[]
     */
    private function allFiles($path)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
        );

        return iterator_to_array($files);
    }

    /**
     * @param $dest
     */
    protected function createDestinationDirectory($dest)
    {
        if (!file_exists($dest)) {
            if (!mkdir($dest, 0777, true)) {
                throw new \RuntimeException('Failed to create the destination directory.');
            }
        }
    }

    /**
     * @param string $src      The source directory.
     * @param string $dest     The destination directory.
     * @param string $filename The filename.
     * @return string
     */
    protected function getDestinationPathName($src, $dest, $filename)
    {
        return str_replace($src, $dest, $filename);
    }

    /**
     * @param $outPathName
     * @return $this
     */
    private function createAnyIntermediateDirectories($outPathName)
    {
        $outDir = dirname($outPathName);

        if (!file_exists($outDir)) {
            if (!mkdir($outDir, 0777, true)) {
                throw new \RuntimeException(sprintf('Failed to create the path "%s".', $outDir));
            }
        }

        return $this;
    }

    /**
     * Write the rendered template to the given destination.
     *
     * @param string $destFilename The destination filename.
     * @param string $rendered     The rendered template.
     * @return $this
     */
    protected function writeRenderedTemplate($destFilename, $rendered)
    {
        if (file_put_contents($destFilename, $rendered) === false) {
            throw new \RuntimeException(sprintf('Could not create the file "%s".', $destFilename));
        }

        return $this;
    }
}
