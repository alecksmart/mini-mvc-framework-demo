<?php
namespace Minimvcf\Controller;

/**
 * Class and Function List:
 * Function list:
 * - init()
 * - actionDefault()
 * - actionCollection()
 * - actionMedia()
 * Classes list:
 * - Page extends Controller
 */
class Page extends Controller
{

    /**
     * Controller-wide init()
     *
     */
    public function init()
    {
        parent::init();
        $this->provideLayoutBasics();
        $this->provideSemanticUI();
    }

    function actionDefault()
    {
        $this->setViewParams(['world' => 'world']);
    }

    function actionCollection()
    {
    }

    function actionMedia()
    {
    }
}
