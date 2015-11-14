<?php
namespace Minimvcf;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - getMethod()
 * - isAjax()
 * - getUri()
 * - getParams()
 * - getParam()
 * Classes list:
 * - Request
 */
class Request
{
    public function __construct()
    {
        if (!isset($_SERVER) || empty($_SERVER)) {
            throw new Exception("Error Processing Request", 1);
        }
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getParams()
    {
        return $_REQUEST;
    }

    /**
     * Get a named parameter
     * @param string $name
     * @return mixed value
     */
    public function getParam($name)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
    }
}
