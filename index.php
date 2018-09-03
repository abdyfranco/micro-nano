<?php

/*
 * Sets the directory separator used throughout the application. DO NOT use this
 * constant when setting URI paths. THE ONLY VALID directory separator in URIs
 * is / (forward-slash).
 */
define('DS', DIRECTORY_SEPARATOR);

/*
 * Sets the root web directory, which is the absolute path to your web directory.
 */
define('ROOTWEBDIR', dirname(__FILE__) . DS);

/*
 * If you have htaccess running that redirects requests to index.php this must
 * be set to true.  If set to false and no htaccess is present, URIs have the
 * form of /index.php/controller/action/param1/.../paramN
 */
define('HTACCESS', file_exists(ROOTWEBDIR . '.htaccess'));

/*
 * Sets the web directory.  This is the relative path to your web directory, and
 * may include index.php if HTACCESS is set to false.
 */
$script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : null);
define('WEBDIR', ($webdir = (!HTACCESS ? $script : (($path = dirname($script)) == '/' || $path == DS ? '' : $path)) . '/') == ROOTWEBDIR ? '/' : $webdir);
unset($script, $webdir, $path);

/*
 * Absolute path to the models directory, where all models are stored.
 */
define('COREDIR', ROOTWEBDIR . 'Core' . DS);

/*
 * Absolute path to the models directory, where all models are stored.
 */
define('MODELSDIR', ROOTWEBDIR . 'Models' . DS);

/*
 * Absolute path to the controllers directory, where all models are stored.
 */
define('CONTROLLERSDIR', ROOTWEBDIR . 'Controllers' . DS);

/*
 * Absolute path to the commands directory, where all models are stored.
 */
define('COMMANDSDIR', ROOTWEBDIR . 'Commands' . DS);

/*
 * Absolute path to the components directory, where all models are stored.
 */
define('COMPONENTSDIR', ROOTWEBDIR . 'Components' . DS);

/*
 * Absolute path to the helpers directory, where all models are stored.
 */
define('HELPERSDIR', ROOTWEBDIR . 'Helpers' . DS);

/*
 * Absolute path to the views directory, where all models are stored.
 */
define('VIEWSDIR', ROOTWEBDIR . 'Views' . DS);

/*
 * Absolute path to the database directory, where all models are stored.
 */
define('DATABASEDIR', ROOTWEBDIR . 'Database' . DS);

/*
 * Load the autoloader
 */
require COREDIR . 'autoloader.php';

/*
 * Initialize app
 */
$app = new \Micro\Core\App();
$app->init();
