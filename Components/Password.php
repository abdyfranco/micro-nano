<?php

namespace Micro\Component;

use Exception;

class Password {
    protected function __construct()
    {
        // Nothing to do
    }

    public static function generate($length = 12, $numbers = true, $symbols = true)
    {
        // Letters
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Numbers
        if ($numbers) {
            $chars .= '0123456789';
        }

        // Symbols
        if ($symbols) {
            $chars .= '@#&%?!-$_^*()[]';
        }

        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        $result = str_shuffle($result);
        $result = substr($result, 0, $length);

        return $result;
    }

    public static function hash($password, $cost = 10)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);

        if ($hash === false) {
            throw new Exception('Bcrypt hashing not supported');
        }

        return $hash;
    }

    public static function getHashInfo($hash)
    {
        return password_get_info($hash);
    }

    public static function needsRehash($hash, $cost = 10)
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    public static function verify($password, $hash)
    {
        if (strlen($hash) === 0) {
            return false;
        }

        return password_verify($password, $hash);
    }
}
