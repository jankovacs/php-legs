<?php

namespace phplegs\base\command;

class BaseMacroCommand
{

    /**
     * @Inject
     * @var injector\api\IInjector
     */
    public $injector;

    private $commands;

    public function __construct()
    {
        $this->commands = [ ];
    }

    final public function execute( $payload )
    {
        $this->registerCommands();

        foreach ( $this->commands as $commandName )
        {
            $this->injector->map( $commandName );
            $actualCommand = $this->injector->getInstance( $commandName );
            $actualCommand->execute( $payload );
            $actualCommand = null;
        }
    }

    protected function registerCommands()
    {

    }

    final protected function add( $command )
    {
        $this->commands[ ] = $command;
    }
}