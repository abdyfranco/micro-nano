<?php

namespace Micro\Core;

use Micro\Helper\Text as Text;

class Router
{
    public static function getUri()
    {
        return trim($_SERVER['REQUEST_URI'], WEBDIR);
    }

    public static function getWebDir()
    {
        return WEBDIR;
    }

    public static function getViewDir()
    {
        return str_replace('/index.php', '', WEBDIR) . str_replace(ROOTWEBDIR, '', VIEWSDIR);
    }

    public static function getController()
    {
        $uri = str_replace(WEBDIR, '', $_SERVER['REQUEST_URI']);
        $get = explode('/', $uri);

        return !empty($get[0]) ? $get[0] : 'index';
    }

    public static function getControllerFunction()
    {
        $uri = str_replace(WEBDIR, '', $_SERVER['REQUEST_URI']);
        $get = explode('/', $uri);

        return !empty($get[1]) ? $get[1] : 'index';
    }

    public static function getView()
    {
        $uri = str_replace(WEBDIR, '', $_SERVER['REQUEST_URI']);
        $view = str_replace('/', '_', $uri);

        return !empty($view) ? $view : 'index';
    }

    public static function loadController($controller, $function)
    {
        // Load Text helper
        $text = new Text();

        $controller = $text->studlyCase($controller);
        $function   = $text->camelCase($function);
        $file       = CONTROLLERSDIR . $controller . '.php';

        if (file_exists($file)) {
            require $file;

            $class_name = '\\Micro\\Controller\\' . $controller;
            $instance   = new $class_name();

            return $instance->$function();
        } else {
            // Show 404 error
            require CONTROLLERSDIR . 'Error.php';

            $class_name = '\\Micro\\Controller\\Error';
            $instance   = new $class_name();

            return $instance->NotFound();
        }
    }

    public static function get()
    {
        $uri = trim($_SERVER['REQUEST_URI'], WEBDIR);
        $get = explode('/', $uri);

        return array_merge($get, $_GET);
    }

    public static function post()
    {
        $post = ['php_input' => file_get_contents('php://input')];

        return array_merge($post, $_POST);
    }
}
