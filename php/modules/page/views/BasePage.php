<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 10:33
 */
class BasePage
{
    protected $smarty;
    private $pageVO;
    private $templateDir;

    public function __construct()
    {
        $this->templateDir = __DIR__ . '/../../../templates/';
        $this->smarty = Singleton::getInstance( Smarty::class );
    }

    public function setPageVO( $pageVO )
    {
        $this->pageVO = $pageVO;
    }

    public function render()
    {
        $viewComponentManager = Singleton::getInstance( ViewComponentManager::class );
        $viewComponentManager->renderComponentsOnPlaces();
        $this->setPageProperties();
        $this->renderTemplate();
        $this->smarty->display( $this->templateDir . 'html5.tpl' );
    }

    private function setPageProperties()
    {
        $this->setPageTitle( $this->pageVO->title );
        $this->setPageDescription( $this->pageVO->description );
        $this->setPageKeywords( $this->pageVO->keywords );
    }

    private function setPageTitle( $title )
    {
        $this->smarty->assign( 'title', $title );
    }

    private function setPageDescription( $description )
    {
        $this->smarty->assign( 'description', $description );
    }

    private function setPageKeywords( $keywords )
    {
        $this->smarty->assign( 'keywords', $keywords );
    }

    private function renderTemplate()
    {
        ob_start();
        $this->smarty->display( $this->templateDir . $this->pageVO->template );
        $this->smarty->assign( 'pageTemplate', ob_get_clean() );
    }
}