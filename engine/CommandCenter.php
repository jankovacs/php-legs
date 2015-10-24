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

class CommandCenter
{
    private $mappings;

    public function __construct( IConfig $config )
    {
        $this->getMappings( $config );
        $this->registerSignals();
    }

    private function getMappings( IConfig $config )
    {
         $this->mappings = $config->getMappings();
    }

    private function registerSignals()
    {
        foreach ( $this->mappings as $commandMapVO )
        {
            $actualSignal = Singleton::getInstance( $commandMapVO->signal );
            $actualSignal->add( $this, 'runCommand' );
        }
    }

    final public function runCommand( $args )
    {
        if ( count( $args ) )
        {
            $commandMapVO = $this->mappings[ $args[ 0 ] ];
            if ( isset( $commandMapVO ) )
            {
                array_shift( $args );
                $this->execute( $commandMapVO->commands, $args );
            }
        }
    }

    private function execute( $commands, $payload )
    {
        foreach ( $commands as $commandClass )
        {
            $actualCommand = new $commandClass;
            call_user_func( [ $actualCommand, 'execute' ], $payload );
            unset( $actualCommand );
        }
    }
}