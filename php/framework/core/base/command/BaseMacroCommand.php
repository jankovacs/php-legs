<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 11:45
 */
class BaseMacroCommand
{

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
            $actualCommand = Singleton::getInstance( $commandName );
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