<?php

namespace phplegs\engine;

use phplegs\base\config\IConfig;
use injector\api\IInjector;

class CommandCenter
{
    private $mappings;
    /**
     * @var IInjector
     */
    private $injector;

    public function __construct( IConfig $config, IInjector $injector )
    {
        $this->injector = $injector;
        $config->setInjector( $this->injector );
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
            $this->injector->map( $commandMapVO->signal )->asSingleton();
            $actualSignal = $this->injector->getInstance( $commandMapVO->signal );
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
            $this->injector->map( $commandClass );
            $actualCommand = $this->injector->getInstance( $commandClass );
            call_user_func( [ $actualCommand, 'execute' ], $payload );
            unset( $actualCommand );
        }
    }
}