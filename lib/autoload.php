<?php
/**
 * Class and Function List:
 * Function list:
 * - minimvcfAutoloader()
 * - minimvcfErrorHandler()
 * Classes list:
 */

/**
 * Define the vendor
 */
define('MYVENDOR', 'rationalplanet');

if (!function_exists('minimvcfAutoloader')) {
    /**
     * Minimvcf autoloader
     * @param string $className
     */
    function minimvcfAutoloader($className)
    {
        $className = ltrim($className, '\\');
        $fileName = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . MYVENDOR;
        $fileName.= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $fileName = $path . DIRECTORY_SEPARATOR . $fileName;
        require_once $fileName;
    }
}

spl_autoload_register('minimvcfAutoloader');

if (!function_exists('minimvcfErrorHandler')) {
    /**
     * Define custom error handling rules
     * Not implemented
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @return bool Stop error propagation
     */
    function minimvcfErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }
        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
                echo "  Fatal error on line $errline in file $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Aborting...<br />\n";
                exit(1);
                break;

            case E_USER_WARNING:
                echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
                echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
                break;

            default:
                echo "Unknown error type: [$errno] $errstr<br />\n";
                break;
        }

        // Don't execute PHP internal error handler
        return true;
    }
}

// no need to work for this yet... maybe later...
// $old_error_handler = set_error_handler("minimvcfErrorHandler");


use Minimvcf\Application;

/**
 * Launch all
 */
$app = Application::getInstance();
$app->run();
