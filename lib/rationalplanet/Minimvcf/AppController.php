<?php
namespace Minimvcf;

use Minimvcf\View\View;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - init()
 * - setView()
 * - getView()
 * - setViewParams()
 * - appendLayoutData()
 * - prependLayoutData()
 * - publishLayoutData()
 * - getBaseCSS()
 * - getBaseJS()
 * Classes list:
 * - AppController
 */
class AppController
{

    private $_viewParams = [];
    private $view;

    private $_title = [];
    private $_meta = [];
    private $_js = [];
    private $_css = [];

    public function __construct()
    {
    }

    /**
     * Lowest level common init(),
     *     must be called form the descendant class
     *
     */
    public function init()
    {
    }

    public function setView(View $view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    /**
     * Description
     * @param type array $params
     * @return type
     */
    public function setViewParams(array $params = [])
    {
        $this->getView()->setParams($params);
    }

    /**
     * Appends predefined vars for layout
     * @param string $dataname (title|meta|js|css)
     * @param string $value
     *
     */
    public function appendLayoutData($dataname, $value)
    {
        $dataname = '_' . $dataname;
        array_push($this->$dataname, $value);
    }

    /**
     * Prepends predefined vars for layout
     * @param string $dataname (title|meta|js|css)
     * @param string $value
     */
    public function prependLayoutData($dataname, $value)
    {
        $dataname = '_' . $dataname;
        array_unshift($this->$dataname, $value);
    }

    /**
     * Formats the above array
     * @param string $dataname
     * @param string $glue
     * @param string $EOL end of line
     * @return string
     */
    public function publishLayoutData($dataname, $glue = "\n", $EOL = PHP_EOL)
    {
        $dataname = '_' . $dataname;
        return implode($glue, $this->$dataname) . $EOL;
    }

    /**
     * Returns web base for CSS
     * @return string
     */
    public function getBaseCSS()
    {
        return '/css/';
    }

    /**
     * Returns web base for JS
     * @return string
     */
    public function getBaseJS()
    {
        return '/js/';
    }
}
