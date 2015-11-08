<?php

namespace phplegs\engine;

use phplegs\base\config\IConfig;
use phplegs\engine\CommandCenter;
use phplegs\engine\RoutingCenter;
use injector\impl\Injector;

class Engine
{
    private $commandCenter;
    private $routingCenter;
    private $smarty;
    private $injector;

    public function __construct()
    {
        $this->injector = new Injector();
    }

    public function addCommandConfig( IConfig $config )
    {
        $this->commandCenter = new CommandCenter( $config, $this->injector );
    }

    public function addRoutingConfig( IConfig $config )
    {
        $routing = isset( $_GET['url'] ) ? $_GET['url'] : '';
        $this->routingCenter = new RoutingCenter( $config, $routing, $this->injector );
    }

    public function addSmarty( $instance )
    {
        $this->injector->map( "Smarty" )->toObject( $instance );
        $this->smarty = $instance;
        $this->smarty->template_dir = __DIR__ . '/../../templates/';
        $this->smarty->compile_dir = __DIR__ . '/../../templates_c/';
        $this->smarty->cache_dir = __DIR__ . '/../../smarty_cache/';
        $this->smarty->debugging = false;
        $this->smarty->caching = false;
        $this->smarty->cache_lifetime = 120;
    }
}