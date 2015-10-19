<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 13:05
 */
class Engine
{
    private $commandCenter;
    private $routingCenter;
    private $smarty;

    public function addApplicationConfig()
    {

    }

    public function addCommandConfig( IConfig $config )
    {
        $this->commandCenter = new CommandCenter( $config );
    }

    public function addRoutingConfig( IConfig $config )
    {
        $routing = isset( $_GET['url'] ) ? $_GET['url'] : '';
        $this->routingCenter = new RoutingCenter( $config, $routing );
    }

    public function setSmarty()
    {
        $this->smarty = Singleton::getInstance( Smarty::class );
        $this->smarty->template_dir = __DIR__ . '/../../templates/';
        $this->smarty->compile_dir = __DIR__ . '/../../templates_c/';
        $this->smarty->cache_dir = __DIR__ . '/../../smarty_cache/';
        $this->smarty->debugging = false;
        $this->smarty->caching = false;
        $this->smarty->cache_lifetime = 120;
    }
}