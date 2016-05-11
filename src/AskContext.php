<?php

namespace Yuloh\Ketch;

class AskContext
{
    /**
     * @var array
     */
    private $variables;

    /**
     * @var array
     */
    private $blacklist;

    /**
     * @param array $blacklist
     */
    public function __construct($blacklist = [])
    {
        $this->blacklist = $blacklist;
    }

    public function __isset($name)
    {
        if ($this->blacklisted($name)) {
            return false;
        }

        return true;
    }

    public function __get($name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        $answer = self::ask("{$name}: ");

        return $this->variables[$name] = self::cast($answer);
    }

    private function blacklisted($name)
    {
        return array_search($name, $this->blacklist) !== false;
    }

    private static function cast($answer)
    {
        if (in_array(strtolower($answer), ['y', 'yes', 'true'])) {
            return true;
        }

        if (in_array(strtolower($answer), ['n', 'no', 'false'])) {
            return false;
        }

        return $answer;
    }

    private static function ask($question)
    {
        // Readline works much better, but it is breaking the tests and
        // I'm not sure how to fix it at the moment.
        if (function_exists('readline') && getenv('KETCH_TEST') === false) {
            return readline($question);
        }

        echo $question;
        $stdin  = fopen('php://stdin', 'r');
        $answer = preg_replace('{\r?\n$}D', '', fgets($stdin, 4096));
        fclose($stdin);

        return $answer;
    }
}
