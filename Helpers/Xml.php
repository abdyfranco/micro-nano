<?php

namespace Micro\Helper;

class Xml
{
    public $tab       = "\t";
    public $root_node = 'result';

    public function xmlEntities($str)
    {
        static $search_chars  = [];
        static $replace_chars = [];

        if (empty($search_chars)) {
            // Replace accepted whitespace characters. All other low ordered bytes are
            // invalid in XML 1.0
            for ($i = 0; $i < 32; $i++) {
                // Encode numeric entities that can be encoded
                if ($i == 9 || $i == 10 || $i == 13) {
                    $search_chars[]  = chr($i);
                    $replace_chars[] = '&#' . $i . ';';
                } // Strip invalid characters from the document
                else {
                    $search_chars[]  = chr($i);
                    $replace_chars[] = null;
                }
            }

            $search_chars  = array_merge(['&', '<', '>', '"', '`'], $search_chars);
            $replace_chars = array_merge(['&amp;', '&lt;', '&gt;', '&quot;', '&apos;'], $replace_chars);
        }

        return str_replace($search_chars, $replace_chars, $str);
    }

    public function makeXml($vars, $encoding = 'UTF-8')
    {
        $xml = '<?xml version="1.0" encoding="' . $encoding . '" ?>';
        $xml .= $this->buildXMLSegment($vars, $this->root_node);

        return $xml;
    }

    private function buildXmlSegment($value, $root_node = 'result', $tab_count = -1)
    {
        $xml = null;
        $tab = $this->tab;

        if (is_numeric($root_node)) {
            $root_node = $this->root_node;
        }

        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value)) {
            foreach ($value as $key => $value2) {
                // Remove any illegal tag characters
                $key = preg_replace('/[^a-z0-9_:.-]/i', '', $key);
                // Recurse
                $sub = $this->buildXmlSegment($value2, $key, ++$tab_count);

                $xml .= "\n";

                $break = is_array($value2) || is_object($value2);

                // If numeric, wrap element with parent's tag
                if (is_numeric($key)) {
                    $xml .= str_repeat($tab, $tab_count) . '<' . $root_node . '>' . $sub . ($break ? "\n" . str_repeat($tab, $tab_count) : '') . '</' . $root_node . '>';
                } else {
                    if ($sub != '') {
                        // If parent is a numeric array, then $sub was given parents tag, so no need to wrap this element in a tag
                        if (is_array($value2) && count(array_diff_key($value2, array_keys(array_keys($value2)))) == 0) {
                            $xml .= str_repeat($tab, $tab_count) . $sub;
                        } // Handle normal elements
                        else {
                            $xml .= str_repeat($tab, $tab_count) . '<' . $key . '>' . $sub . ($break ? "\n" . str_repeat($tab, $tab_count) : '') . '</' . $key . '>';
                        }
                    } // Handle elements with no content
                    else {
                        $xml .= str_repeat($tab, $tab_count) . '<' . $key . ' />';
                    }
                }
                // Bubble up
                $tab_count--;
            }
        } else {
            $xml .= $this->xmlEntities($value);
        }

        return $xml;
    }
}
