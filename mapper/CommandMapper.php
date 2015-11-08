<?php

namespace phplegs\mapper;

use phplegs\mapper\vo\CommandMapVO;

class CommandMapper implements IMapper
{
    private $mappings;

    public function __construct()
    {
        $this->mappings = [ ];
    }

    final public function map( $signal, $command )
    {
        if ( isset( $this->mappings[ $signal ] ) )
        {
            $commandMapVO = $this->mappings[ $signal ];
        } else
        {
            $commandMapVO = new CommandMapVO();
        }

        $commandMapVO->signal = $signal;
        $commandMapVO->commands[ ] = $command;
        $this->mappings[ $signal ] = $commandMapVO;
    }

    final public function unmap( $signal, $command )
    {
        $commandMapVO = $this->mappings[ $signal ];
        if ( $commandMapVO->signal == $signal )
        {
            $indexOfCommand = array_search( $command, $commandMapVO->commands );
            array_splice( $commandMapVO->comannds, $indexOfCommand );
            if ( !count( $commandMapVO->commands ) )
            {
                array_splice( $this->mappings, $key );
            }
        }
    }

    final public function getMappings()
    {
        return $this->mappings;
    }
}