<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 11:44
 */
class SetIndexPageMacroCommand extends BaseMacroCommand
{
    protected function registerCommands()
    {
        $this->add( RoutingCommand::class );
        $this->add( HeaderCommand::class );
        $this->add( SetPageCommand::class );
    }
}