<?php

namespace phplegs\engine;

use phplegs\base\config\IConfig;

class RoutingCenter
{
    private $mappings;
    private $routing;
    private $injector;

    public function __construct( $config, $routing, $injector )
    {
        $this->injector = $injector;
        $this->routing = $routing;
        $this->getMappings( $config );
        $this->switchOnIndexPage();
        $this->removeSlashFromEndOfUrl();
        $this->dispatchSignals();
    }

    private function getMappings( IConfig $config )
    {
        $this->mappings = $config->getMappings();
    }

    private function removeSlashFromEndOfUrl()
    {
        $urlLength = strlen( $this->routing );
        if ( strpos( $this->routing, '/' ) == $urlLength - 1 )
        {
            $this->routing = substr( $this->routing, 0, $urlLength - 1 );
        }
    }

    private function switchOnIndexPage()
    {
        if ( $this->routing == '' )
        {
            $this->routing = 'index';
        }
    }

    private function dispatchSignals()
    {
        $pageFound = false;
        foreach ( $this->mappings as $routingMapVO )
        {
            if ( $this->routing == $routingMapVO->routing )
            {
                $this->dispatchActualSignal( $routingMapVO->signal, $routingMapVO->routing );
                $pageFound = true;
            }
        }

        if ( !$pageFound )
        {
            $this->dispatchActualSignal( RoutingSignal::class, '404' );
        }
    }

    private function dispatchActualSignal( $signalName, $payload )
    {
        $this->injector->map( $signalName )->asSingleton();
        $actualSignal = $this->injector->getInstance( $signalName );
        $actualSignal->dispatch( $payload );
    }
}