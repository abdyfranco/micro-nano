<?php

namespace Micro\Core;

use Exception;

class Command {
    private $text;

    protected function isCli()
    {
        return substr(php_sapi_name(), 0, 3) == 'cli';
    }

    protected function color($text, $color = 'default')
    {
        $colors = [
            'black' => 30,
            'blue' => 34,
            'green' => 32,
            'cyan' => 36,
            'red' => 31,
            'purple' => 35,
            'brown' => 33,
            'gray' => 37
        ];

        if (array_key_exists($color, $colors)) {
            $this->text .= "\033[" . $colors[$color] . 'm' . $text . "\033[0m";
        } else {
            $this->text .= $text;
        }

        return $this;
    }

    protected function print($text = null)
    {
        if (empty($text)) {
            $text = $this->text;
        }

        echo $text . "\n";
    }

    protected function executeCommand($command, $bypass = false)
    {
        $output = '';

        if ($bypass) {
            passthru($command, $output);
        } else {
            exec($command, $output);
        }

        return $output;
    }

    protected function safe($argument)
    {
        return escapeshellarg($argument);
    }
}