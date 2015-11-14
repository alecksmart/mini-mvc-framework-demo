<?php
namespace Minimvcf;

use Minimvcf\Request;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - __clone()
 * - getInstance()
 * - addRoute()
 * - _filterParams()
 * - actionName()
 * - controllerName()
 * - matchRequest()
 * Classes list:
 * - Router
 */
final class Router
{

    const NOT_FOUND = 0;
    const FOUND = 1;

    protected static $_instance;

    private $routesCollection = [];
    private $defaultRoute = ['allowedMethods' => ['GET'], 'callback' => ['controller' => 'Page', 'action' => 'default'], 'requireAjax' => 0];
    private $notFoundRoute = ['allowedMethods' => ['GET'], 'callback' => ['controller' => 'Error', 'action' => 'error404'], 'requireAjax' => 0];
    private $errorRoute = ['allowedMethods' => ['GET'], 'callback' => ['controller' => 'Error', 'action' => 'default'], 'requireAjax' => 0];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * Singleton
     * @return Router
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addRoute($uriRule, array $methods, $isAjax, array $callback)
    {
        $this->routesCollection[$uriRule] = ['allowedMethods' => $methods, 'callback' => $callback, 'requireAjax' => $isAjax];
    }

    /**
     * Override REQUEST with URI values
     * @param array $urlParts
     * @return array
     */
    private function _filterParams(array $urlParts)
    {
        $params = $_REQUEST;
        $chunks = array_chunk($urlParts, 2);
        if (empty($chunks)) {
            return $params;
        }
        foreach ($chunks as $chunk) {
            if (!trim($chunk[0])) {
                continue;
            }
            $params[$chunk[0]] = isset($chunk[1]) ? $chunk[1] : null;
        }
        return $params;
    }

    public static function actionName($urlPart)
    {
        return sprintf('action%s', ucfirst($urlPart));
    }

    public static function controllerName($urlPart)
    {
        return sprintf('Minimvcf\\Controller\\%s', $urlPart);
    }

    /**
     * Check the router rules for a match
     *
     * @todo Create a separate class to decribe and handle routing
     *
     * @param Request $request
     * @return array routing basics
     *
     */
    public function matchRequest(Request $request)
    {

        $url = explode('?', $request->getUri());
        $uri = $url[0];

        if (in_array($uri, ['', '/'])) {
            return ['result' => self::FOUND, 'route' => $this->defaultRoute, 'params' => []];
        }

        foreach ($this->routesCollection as $key => $route) {
            // check method rules
            if (!in_array(strtoupper($request->getMethod()), $route['allowedMethods'])) {
                continue;
            }

            // check if ajax is required
            if ($request->isAjax() != $route['requireAjax']) {
                continue;
            }

            $pattern = '@^/' . $key . '(/{0,1}$|/.+)@';

            if (preg_match($pattern, $uri)) {
                // check controller
                $classname = self::controllerName($route['callback']['controller']);
                if (!class_exists($classname)) {
                    throw new \Exception('Controller error!');
                }

                $fromUrl = explode('/', preg_replace('@^/@', '', $uri));
                array_shift($fromUrl);

                // override action from url
                if (isset($fromUrl[0]) && $fromUrl[0] != "") {
                    $action = self::actionName($fromUrl[0]);
                    if (method_exists($classname, $action)) {
                        $route['callback']['action'] = $fromUrl[0];
                        array_shift($fromUrl);
                    }
                }

                return ['result' => self::FOUND, 'route' => $route, 'params' => $this->_filterParams($fromUrl) ];
            }
        }

        return ['result' => self::NOT_FOUND, 'route' => $this->notFoundRoute, 'params' => []];
    }
}
