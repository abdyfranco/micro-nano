<?php

namespace Micro\Component;

class Hashing
{
    private $data = '';

    public static function hmacHash($algorithm, $data, $key, $raw = false, $file = false)
    {
        if ($file) {
            return hash_hmac_file($algorithm, $data, $key, $raw);
        } else {
            return hash_hmac($algorithm, $data, $key, $raw);
        }
    }

    public static function hash($algorithm, $data, $raw = false, $file = false)
    {
        if ($file) {
            return hash_file($algorithm, $data, $raw);
        } else {
            return hash($algorithm, $data, $raw);
        }
    }

    public static function listHashAlgorithms()
    {
        return hash_algos();
    }

    public static function compare($hash, $user_hash)
    {
        return hash_equals($hash, $user_hash);
    }

    public function start($algorithm)
    {
        $this->data = hash_init($algorithm);
    }

    public function addData($data, $file = false)
    {
        if ($file) {
            hash_update_file($this->data, $data);
        } else {
            hash_update($this->data, $data);
        }
    }

    public function finish()
    {
        $hash       = hash_final($this->data);
        $this->data = null;

        return $hash;
    }
}
