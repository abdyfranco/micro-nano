<?php

namespace Micro\Core;

use Exception;
use Micro\Helper\Text as Text;

class Language
{
    private $language;
    private $dictionary;

    public function setLanguage($language)
    {
        // Load Text helper
        $text = new Text();

        // Set language
        $language  = $text->snakeCase(trim($language));
        $directory = LANGUAGESDIR . $language;

        if (is_dir($directory)) {
            $this->language = $language;
        } else {
            throw new Exception('The language "' . $language . '" could not be found');
        }
    }

    public function loadDictionary($dictionary)
    {
        // Load Text helper
        $text = new Text();

        $dictionary = $text->snakeCase(trim($dictionary));
        $file       = LANGUAGESDIR . $this->language . DS . $dictionary . '.php';

        if (file_exists($file)) {
            require $file;

            $this->dictionary = $lang;
        } else {
            throw new Exception('The dictionary "' . $dictionary . '" could not be found');
        }
    }

    public function text($key)
    {
        $output = isset($this->dictionary[$key]) ? $this->dictionary[$key] : $key;
        $args   = func_get_args();

        if (count($args) > 2) {
            $output = call_user_func_array('sprintf', $args);
        }

        return $output;
    }

    protected function getLanguage()
    {
        return $this->language;
    }
}
