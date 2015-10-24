<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 13:21
 */

namespace engine;

use base\config\IConfig;
use pattern\Singleton;

class RoutingCenter
{
    private $mappings;
    private $routing;

    public function __construct( $config, $routing )
    {
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
        $actualSignal = Singleton::getInstance( $signalName );
        $actualSignal->dispatch( $payload );
    }
}