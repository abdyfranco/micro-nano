<?php

$mapping = [
    'Micro\Core\Controller' => COREDIR . 'Controller.php',
    'Micro\Core\Model' => COREDIR . 'Model.php',
    'Micro\Core\Router' => COREDIR . 'Router.php',
    'Micro\Core\Command' => COREDIR . 'Command.php',
    'Micro\Core\App' => COREDIR . 'App.php',
    'Micro\Helper\Arrayment' => HELPERSDIR . 'Arrayment.php',
    'Micro\Helper\Html' => HELPERSDIR . 'Html.php',
    'Micro\Helper\Javascript' => HELPERSDIR . 'Javascript.php',
    'Micro\Helper\Json' => HELPERSDIR . 'Json.php',
    'Micro\Helper\Text' => HELPERSDIR . 'Text.php',
    'Micro\Helper\Xml' => HELPERSDIR . 'Xml.php',
    'Micro\Component\Encryption' => COMPONENTSDIR . 'Encryption.php',
    'Micro\Component\Filesystem' => COMPONENTSDIR . 'Filesystem.php',
    'Micro\Component\Hashing' => COMPONENTSDIR . 'Hashing.php',
    'Micro\Component\Password' => COMPONENTSDIR . 'Password.php'
];

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);
