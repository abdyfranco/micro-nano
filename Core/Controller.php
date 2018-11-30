<?php

namespace Micro\Core;

use Exception;
use Micro\Helper\Text as Text;

class Controller
{
    protected $language;
    protected $view;
    protected $view_dir;
    protected $web_dir;
    protected $get  = [];
    protected $post = [];
    protected $vars = [];

    public function __construct()
    {
        // Initialize language object
        $this->language = new Language();

        $this->view     = Router::getView();
        $this->view_dir = Router::getViewDir();
        $this->web_dir  = Router::getWebDir();
        $this->get      = Router::get();
        $this->post     = Router::post();
    }

    protected function setViewVariable($name, &$value)
    {
        $this->vars[$name] = $value;
    }

    protected function setView($view)
    {
        // Set request variables
        $view_dir = $this->view_dir;
        $web_dir  = $this->web_dir;

        $get  = $this->get;
        $post = $this->post;

        // Set variables
        foreach ($this->vars as $key => $value) {
            ${$key} = $value;
        }

        require VIEWSDIR . ltrim($view, '/') . '.php';
    }

    protected function loadModel($model)
    {
        // Load Text helper
        $text = new Text();

        $model = $text->studlyCase($model);
        $file  = MODELSDIR . $model . '.php';

        if (file_exists($file)) {
            require $file;

            $class_name     = '\\Micro\\Model\\' . $model;
            $this->{$model} = new $class_name();
        } else {
            throw new Exception('The model "' . $model . '" could not be found');
        }
    }
}
