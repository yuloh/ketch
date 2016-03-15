<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use Yuloh\Expect\Expect;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $root;

    private $ketchBinary;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->root = realpath(__DIR__ . '/../../');
        $this->ketchBinary = $this->root . '/ketch';
        $this->cwd = realpath(__DIR__ . '/../working-directory');
    }

    /**
     * @Given I have ketch installed
     */
    public function iHaveKetchInstalled()
    {
        // It's always installed for the repo :)
    }

    /**
     * @When I create a project named :project using the :template template and the answers:
     */
    public function iCreateAProjectNamedUsingTheTemplateAndTheAnswers($project, $template, TableNode $table)
    {
        $cmd = "php {$this->ketchBinary} create {$template} {$project}";

        $e = Expect::spawn($cmd, $this->cwd)
            ->debug();

        foreach ($table->getHash() as $expectation) {
            $e
                ->expect($expectation['question'])
                ->send($expectation['answer']);
        }

        $e->expect('Project ready!')
            ->run();
    }

    /**
     * @Then the project :project should be created
     */
    public function theProjectShouldBeCreated($project)
    {
        Assert::assertFileExists($this->cwd . '/' . $project);
    }

    /**
     * @Then the composer.json :key for :project should read :name
     */
    public function theComposerJsonNameShouldRead($key, $project, $name)
    {
        $composer = json_decode(file_get_contents("{$this->cwd}/{$project}/composer.json"), true);

        Assert::assertArrayHasKey($key, $composer);
        Assert::assertSame($name, $composer[$key]);
    }

    /**
      * @AfterScenario
      */
     public function cleanCwd(AfterScenarioScope $scope)
     {
         $contents = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->cwd, \FilesystemIterator::SKIP_DOTS),
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
     }
}
