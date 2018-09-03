<?php

namespace Micro\Helper;

class Text {
    public function camelCase($text)
    {
        $result = '';
        $text = $this->snakeCase($text);
        $text = explode('_', $text);

        foreach ($text as $word) {
            $result .= ucfirst($word);
        }

        return lcfirst($result);
    }

    public function studlyCase($text)
    {
        $text = $this->camelCase($text);

        return ucfirst($text);
    }

    public function snakeCase($text)
    {
        $text = trim($text);
        $text = preg_replace('/(?<!^)[A-Z]/', '_$0', $text);
        $text = str_replace(' ', '_', $text);
        $text = str_replace('-', '_', $text);

        return strtolower($text);
    }

    public function kebabCase($text)
    {
        $text = $this->snakeCase($text);
        $text = str_replace('_', '-', $text);

        return $text;
    }

    public function uri($text)
    {
        $text = preg_replace('/[^A-Za-z0-9 ]/', '', $text);

        return $this->kebabCase($text);
    }

    public function title($text)
    {
        return ucwords($text);
    }

    public function capitalize($text)
    {
        return ucfirst($text);
    }

    public function lowercase($text)
    {
        return strtolower($text);
    }

    public function uppercase($text)
    {
        return strtoupper($text);
    }

    public function truncate($text, $limit)
    {
        return substr($text, 0, $limit) . ($limit < strlen($text) ? '...' : null);
    }

    public function removeTags($text)
    {
        return strip_tags($text);
    }

    public function contains($haystack, $needle)
    {
        return $needle != '' && mb_strpos($haystack, $needle) !== false;
    }

    public function censorWords($text, array $words, $replacement = '****')
    {
        foreach ($words as $word) {
            $text = str_replace($word, $replacement, $text);
        }
        
        return $text;
    }
}