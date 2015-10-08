<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 21:10
 */
class RoutingModel
{
    private $routings;

    public function setActualPage( $routing, $settingsJson )
    {
        $this->routings = $settingsJson;
        $routingModelSignal = Singleton::getInstance( RoutingModelSignal::class );
        $routingModelSignal->dispatch( $this->createPageVO( $routing, true ) );
    }

    private function createPageVO( $pageName, $createNotFound = false )
    {
        $pageVO = new PageVO();
        if ( $createNotFound && !isset( $this->routings->$pageName ) )
        {
            $pageName = 'notFound';
        }
        $pageVO->name = $pageName;
        $pageVO->template = $this->routings->$pageName->template;
        $pageVO->title = $this->routings->$pageName->title;
        $pageVO->description = $this->routings->$pageName->description;
        $pageVO->keywords = $this->routings->$pageName->keywords;

        if ( isset( $this->routings->$pageName->menu ) )
        {
            $pageVO->menu = $this->routings->$pageName->menu;
        }

        return $pageVO;
    }

    public function getRoutingSettings()
    {
        return $this->routings;
    }

    public function getPageVO( $pagename )
    {
        return $this->createPageVO( $pagename );
    }
}