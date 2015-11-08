<?php

namespace phplegs\mapper;

use phplegs\mapper\vo\RoutingMapVO;

class RoutingMapper implements IMapper
{
    private $mappings;

    public function __construct()
    {
        $this->mappings = [ ];
    }

    final public function map( $routing, $signal )
    {
        $routingMapVO = new RoutingMapVO();
        $routingMapVO->signal = $signal;
        $routingMapVO->routing = $routing;
        $this->mappings[ $routing ] = $routingMapVO;
    }

    final public function unmap( $routing, $signal )
    {
        $routingMapVO = $this->mappings[ $routing ];
        if ( isset( $routingMapVO ) )
        {
            array_splice( $this->mappings, $key );
        }
    }

    final public function getMappings()
    {
        return $this->mappings;
    }
}