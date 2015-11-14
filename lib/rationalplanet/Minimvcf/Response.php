<?php
namespace Minimvcf;

use Minimvcf\View\View;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - bootstrap()
 * - send()
 * Classes list:
 * - Response
 */
class Response
{
    private $request;
    private $headers = [];
    private $body = '';

    private $controller;

    public function __construct()
    {
        $this->headers[] = 'Content-Type: text/html; charset=utf-8';
        $this->request = Application::getRequest();
        $this->bootstrap();
    }

    /**
     * Boostrap and glue all together
     * @return type
     */
    protected function bootstrap()
    {

        // fb(sprintf('---------- %s ------------', __METHOD__));
        $routing = Application::getRouting();

        // instantiate controller
        $controller = Router::controllerName(Application::getController());
        $action = Router::actionName(Application::getAction());
        $actor = new $controller();
        $actor->init();

        // initialize view
        $actor->setView(new View());

        // get basics
        $layoutName = $actor->getView()->getLayoutName();
        $layoutPath = $actor->getView()->getLayoutPath();

        $viewName = strtolower($routing['route']['callback']['action']);
        $actor->getView()->setViewName($viewName);
        $viewPath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . strtolower($routing['route']['callback']['controller']));
        $actor->getView()->setViewPath($viewPath);

        // run action, setting and overriding values happen there
        $actor->$action();

        // action set to null view and layout file names
        if (is_null($actor->getView()->getViewName()) && is_null($actor->getView()->getLayoutName())) {
            $this->body = $actor->getView()->getOutput();

            // the output seems to be fully redefined, pass to next step
            return;
        }

        // render view
        $viewPath = $actor->getView()->getViewPath() . DIRECTORY_SEPARATOR . $actor->getView()->getViewName() . '.php';
        if (!file_exists($viewPath)) {
            throw new \Exception(sprintf('View %s not found in path: %s', $actor->getView()->getViewName(), $actor->getView()->getViewPath()));
        }
        $this->body = $actor->getView()->render($viewPath, $actor->getView()->getParams());
        if (is_null($actor->getView()->getLayoutName())) {
            // no layout, pass to next step
            return;
        }

        // render layout
        $layoutPath = $actor->getView()->getLayoutPath() . DIRECTORY_SEPARATOR . $actor->getView()->getLayoutName() . '.php';
        if (!file_exists($layoutPath)) {
            throw new \Exception(sprintf('Layout %s not found in path: %s', $actor->getView()->getLayoutName(), $actor->getView()->getLayoutPath()));
        }
        $params = $actor->getView()->getParams();
        $params['_view_content_'] = $this->body;
        $params['_title_'] = $actor->publishLayoutData('title', ' :: ', '');
        $params['_meta_'] = $actor->publishLayoutData('meta');
        $params['_css_'] = $actor->publishLayoutData('css');
        $params['_js_'] = $actor->publishLayoutData('js');
        $this->body = $actor->getView()->render($layoutPath, $params);
    }

    /**
     * Send headers and response
     */
    public function send()
    {
        // send response headers
        if (!empty($this->headers)) {
            foreach ($this->headers as $header) {
                header($header);
            }
        }

        // send response body
        echo $this->body;
    }
}
