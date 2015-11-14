<?php
namespace Minimvcf;

/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - setParams()
 * - getParams()
 * - setViewName()
 * - getViewName()
 * - setViewPath()
 * - getViewPath()
 * - setLayoutName()
 * - getLayoutName()
 * - setLayoutPath()
 * - getLayoutPath()
 * - setOutput()
 * - getOutput()
 * - render()
 * - _cleanup()
 * Classes list:
 * - AppView
 */

class AppView
{

    private $_viewFileName;

    private $_viewFilePath;

    private $_layoutFileName;

    private $_layoutFilePath;

    private $_viewParams = [];

    private $output = '';

    public function __construct()
    {
        $this->setLayoutPath(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'template');
        $this->setLayoutName('common');
    }

    public function setParams(array $params)
    {
        $this->_viewParams = array_merge($this->_viewParams, $params);
    }

    public function getParams()
    {
        return $this->_viewParams;
    }

    public function setViewName($name)
    {
        $this->_viewFileName = $name;
    }

    public function getViewName()
    {
        return $this->_viewFileName;
    }

    public function setViewPath($path)
    {
        $this->_viewFilePath = $path;
    }

    public function getViewPath()
    {
        return $this->_viewFilePath;
    }

    public function setLayoutName($name)
    {
        $this->_layoutFileName = $name;
    }

    public function getLayoutName()
    {
        return $this->_layoutFileName;
    }

    public function setLayoutPath($path)
    {
        $this->_layoutFilePath = $path;
    }

    public function getLayoutPath()
    {
        return $this->_layoutFilePath;
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Render a view or layout
     * @param string $filePath
     * @param string $params
     * @return string
     */
    public function render($filePath, $params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $$key = $value;
            }
        }
        ob_start(null, 0, PHP_OUTPUT_HANDLER_REMOVABLE);
        require $filePath;
        $out = $this->_cleanup(ob_get_contents());
        ob_end_clean();
        return $out;
    }

    /**
     * Basic cleanup html output
     * @param string $input
     * @return string
     */
    private function _cleanup($input)
    {
        return preg_replace(array(
            '/<!--(.*)-->/Uis',
            "/[[:blank:]]+/"
        ), array(
            '',
            ' '
        ), str_replace(array(
            "\n",
            "\r",
            "\t"
        ), '', $input));
    }
}
