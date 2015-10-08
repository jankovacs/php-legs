<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 20:30
 */
class RoutingConfigurator extends BaseMappingConfigurator
{
    protected function getMapperType()
    {
        return MapperFactory::ROUTING_MAPPER;
    }

    protected function setMappings()
    {
        $this->mapper->map( 'index', IndexPageSignal::class );
    }
}