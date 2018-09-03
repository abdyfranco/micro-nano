<?php

namespace Micro\Helper;

use Exception;

class Json {
    private $json = '';

    public function jsonEncode($object)
    {
        $json = json_encode($object);

        if ($this->jsonError()) {
            $this->json = $json;

            return $this->json;
        }
    }

    public function jsonDecode($json)
    {
        $json = json_decode($json);

        if ($this->jsonError()) {
            return $json;
        }
    }

    public function printJson()
    {
        echo $this->json;
    }

    private function jsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                throw new Exception('The maximum stack depth has been exceeded');
            case JSON_ERROR_STATE_MISMATCH:
                throw new Exception('Invalid JSON string');
            case JSON_ERROR_CTRL_CHAR:
                throw new Exception('Control character error, possibly incorrectly encoded');
            case JSON_ERROR_SYNTAX:
                throw new Exception('Syntax error or invalid JSON string');
            case JSON_ERROR_UTF8:
                throw new Exception('Malformed UTF-8 characters, possibly incorrectly encoded');
            case JSON_ERROR_RECURSION:
                throw new Exception('One or more recursive references in the value to be encoded');
            case JSON_ERROR_UNSUPPORTED_TYPE:
                throw new Exception('A value of a type that cannot be encoded was given');
        }

        return json_last_error() === JSON_ERROR_NONE;
    }
}
