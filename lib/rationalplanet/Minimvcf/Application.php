<?php
namespace Minimvcf;

use Minimvcf\Request;
use Minimvcf\Response;
use Minimvcf\Router;
use Minimvcf\Controller\Page;

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\NotCache;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - __clone()
 * - getInstance()
 * - getConfig()
 * - getRouting()
 * - getController()
 * - getAction()
 * - getParams()
 * - getParam()
 * - getRequest()
 * - run()
 * Classes list:
 * - Application
 */
class Application
{

    protected static $_instance;

    private $config;
    private $request;
    private $routing = [];
    private $response;
    private $page;
    private $cache;

    private function __construct()
    {
        try {
            $this->config = require_once(dirname(__FILE__) . '/../../config.php');
            $this->cache = new Cache(new NotCache());
            $this->request = new Request();
            Router::getInstance()->addRoute('collection', ['GET'], 0, ['controller' => 'Page', 'action' => 'collection']);
            Router::getInstance()->addRoute('media', ['GET'], 0, ['controller' => 'Page', 'action' => 'media']);
        } catch (\Exception $e) {
            fb($e->getMessage);
            die('Application error!');
        }
    }

    private function __clone()
    {
    }

    /**
     * Singleton
     * @return Application
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Get config
     * @return array
     */
    public static function getConfig()
    {
        return self::getInstance()->config;
    }

    /**
     * Get routing
     * @return array
     */
    public static function getRouting()
    {
        return self::getInstance()->routing;
    }

    /**
     * Get controller name
     * @return mixed string or null
     */
    public static function getController()
    {
        $routing = self::getRouting();
        return !empty($routing) ? $routing['route']['callback']['controller'] : null;
    }

    /**
     * Get action name
     * @return mixed string or null
     */
    public static function getAction()
    {
        $routing = self::getRouting();
        return !empty($routing) ? $routing['route']['callback']['action'] : null;
    }

    /**
     * Get all defined parameters
     * @return mixed array or null
     */
    public static function getParams()
    {
        $routing = self::getRouting();
        return !empty($routing) ? $routing['params'] : null;
    }

    /**
     * Get a parameter by name
     * @return mixed or null
     */
    public static function getParam($name)
    {
        $routing = self::getRouting();
        return !empty($routing) && isset($routing['params'][$name]) ? $routing['params'][$name] : null;
    }

    /**
     * Request getter
     * @return Request
     */
    public static function getRequest()
    {
        return self::getInstance()->request;
    }

    /**
     * Dispatcher and bootstrapper
     */
    public function run()
    {

        // fb(sprintf('---------- %s ------------', __METHOD__));
        try {
            $this->routing = Router::getInstance()->matchRequest($this->request);

            // fb($this->routing);
            // fb(['controller' => self::getController(), 'action' => self::getAction(),]);
            // fb(self::getParams());
            $this->response = new Response();
            $this->response->send();
        } catch (\Exception $e) {
            fb($e->getMessage());
            die('Application error!');
        }
    }
}
