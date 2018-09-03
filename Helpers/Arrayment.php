<?php

namespace Micro\Helper;

class Arrayment
{
    public static function first(array $array)
    {
        if (is_array($array)) {
            $array = array_values($array);

            return $array[0];
        }

        return false;
    }

    public static function last(array $array)
    {
        if (is_array($array)) {
            $array = array_reverse($array);

            return $this->first($array);
        }

        return false;
    }

    public static function keyExists($key, array $array)
    {
        if (is_string($key) && is_array($array)) {
            return array_key_exists($key, $array);
        }

        return false;
    }

    public static function valueExists($value, array $array)
    {
        if (is_array($array)) {
            foreach ($array as $element) {
                if ($element == $value) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function add(&$array, $value, $key = null)
    {
        if (is_array($array)) {
            if (!empty($key) && is_string($key)) {
                $array[$key] = $value;
            } else {
                $array[] = $value;
            }

            return $array;
        }
    }

    public static function remove(&$array, $key)
    {
        if (is_array($array)) {
            unset($array[$key]);

            return $array;
        }
    }

    public static function get(array $array, $key)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
    }

    public static function random(array $array)
    {
        if (is_array($array)) {
            return $array[array_rand($array)];
        }

        return false;
    }

    public static function merge($recursive = false)
    {
        $args = func_get_args();
        $args = array_shift($args);

        if ($recursive) {
            return call_user_func_array('array_merge_recursive', $args);
        } else {
            return call_user_func_array('array_merge', $args);
        }
    }

    public function combine(array $array1, array $array2)
    {
        if (is_array($array1) && is_array($array2)) {
            return array_combine($array1, $array2);
        }

        return false;
    }

    public function collapse($array, $multi_level = false)
    {
        $result = [];

        if (is_array($array)) {
            foreach ($array as $sub_array) {
                if (is_array($sub_array)) {
                    foreach ($sub_array as $value) {
                        if (is_array($value) && $multi_level) {
                            $result = array_merge($result, $this->collapse($value, true));
                        } else {
                            $result[] = $value;
                        }
                    }
                } else {
                    $result[] = $sub_array;
                }
            }

            return $result;
        }

        return $array;
    }

    public function reduce($array, callable $callback, $initial = null)
    {
        return array_reduce($array, $callback, $initial);
    }

    public function split($array)
    {
        return ['keys' => array_keys($array), 'values' => array_values($array)];
    }

    public function dotMatrix(array $array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, $this->dotMatrix($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    public function filter(array $array, callable $callback = null)
    {
        if (is_callable($callback)) {
            return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
        } else {
            return array_filter($array);
        }
    }
}
