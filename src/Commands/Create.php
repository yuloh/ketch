<?php

namespace Yuloh\Ketch\Commands;

use Guzzle\Http\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Yuloh\Ketch\Ketch;

class Create
{
    /**
     * Execute the command.
     *
     * @param  string $name
     * @param  string $template
     * @param  OutputInterface $output
     * @return void
     */
    public function __invoke($name, $template, OutputInterface $output)
    {
        $repositoryNwo = trim($template, '/');
        $dest          = getcwd() . DIRECTORY_SEPARATOR . $name;
        $tmp           = $this->createTmp();
        $tarFilename   = $this->createTarName($tmp);

        $this
            ->download($repositoryNwo, $tarFilename)
            ->extractTar($tarFilename, $tmp);

        $src = $this->findTemplate($tmp, $repositoryNwo);

        (new Ketch())->generate($src, $dest);

        $this->deleteDir($tmp);

        $output->writeln('<comment>Project ready!</comment>');
    }

    private function createTmp()
    {
        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ketch_' . md5(time() . uniqid());
        mkdir($tmp);

        return $tmp;
    }

    private function createTarName($dir)
    {
        return $dir . DIRECTORY_SEPARATOR . 'ketch_' . md5(time() . uniqid()) . '.tar.gz';
    }

    /**
     * Download the repo to the given path.
     *
     * @param string $repositoryNwo
     * @param string $writePath
     * @return $this
     */
    private function download($repositoryNwo, $writePath)
    {
        $readStream = (new Client())
            ->get(sprintf('https://github.com/%s/tarball/master', $repositoryNwo))
            ->send()
            ->getBody()
            ->getStream();

        rewind($readStream);
        $writeStream = fopen($writePath, 'w+');
        stream_copy_to_stream($readStream, $writeStream);

        return $this;
    }

    /**
     * Recursively delete the given directory and it's contents.
     *
     * @param string $directory
     * @return bool
     */
    private function deleteDir($directory)
    {
        $contents = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        /** @var \SplFileInfo $file */
        foreach ($contents as $file) {
            if ($file->getType() === 'dir') {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        return rmdir($directory);
    }

    /**
     * Find the template path in the given directory.
     *
     * @param string $dir
     * @param string $repositoryNwo
     * @return string
     */
    private function findTemplate($dir, $repositoryNwo)
    {
        $matches = glob($dir . DIRECTORY_SEPARATOR . str_replace('/', '-', $repositoryNwo) . '-*');

        $repoDirectory = reset($matches);

        $templatePath = $repoDirectory . DIRECTORY_SEPARATOR . 'template';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException('Could not find the "template" directory in the given repository.');
        }

        return $templatePath;
    }

    /**
     * @param string $tarFilename
     * @param string $dest
     * @return $this
     */
    protected function extractTar($tarFilename, $dest)
    {
        $archive = new \PharData($tarFilename);
        $archive->extractTo($dest);
        unlink($tarFilename);

        return $this;
    }
}
