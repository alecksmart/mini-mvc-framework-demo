<?php
namespace Minimvcf\Controller;

use Minimvcf\Application;
use Minimvcf\AppController;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - init()
 * - provideLayoutBasics()
 * - provideSemanticUI()
 * Classes list:
 * - Controller extends AppController
 */
class Controller extends AppController
{

    protected $_includes = [];

    public function __construct()
    {
    }

    /**
     * Application-wide init()
     * @return type
     */
    public function init()
    {
        parent::init();
        $this->appendLayoutData('title', Application::getConfig() ['sitename']);
    }

    public function provideLayoutBasics()
    {
        $this->appendLayoutData('css', '<link rel="stylesheet" type="text/css" href="/css/index.css"/>');
        $this->_includes[] = 'jquery.min.js';
        $this->appendLayoutData('js', '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>');
        $this->appendLayoutData('js', '<script src="/components/namespace-js/src/namespace.js"></script>');
        $this->appendLayoutData('js', '<script src="/js/index.min.js"></script>');
    }

    /**
     * @see http://osscdn.com/#/semantic-ui
     * @see http://semantic-ui.com/kitchen-sink.html
     */
    public function provideSemanticUI()
    {
        $this->prependLayoutData('css', '<link rel="stylesheet" type="text/css" href="//oss.maxcdn.com/semantic-ui/2.1.6/semantic.min.css"/>');
        $this->_includes[] = 'semantic.min.js';
        $this->appendLayoutData('js', '<script src="//oss.maxcdn.com/semantic-ui/2.1.6/semantic.min.js"></script>');
    }
}
