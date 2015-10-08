<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 17:54
 */
class CommandConfigurator extends BaseMappingConfigurator
{
    protected function getMapperType()
    {
        return MapperFactory::COMMAND_MAPPER;
    }

    protected function setMappings()
    {
        $this->mapper->map( IndexPageSignal::class, SetIndexPageMacroCommand::class );
        $this->mapper->map( RegisterViewContentSignal::class, RegisterViewContentCommand::class );
        $this->mapper->map( AddComponentToPlaceSignal::class, AddComponentToPlaceCommand::class );
    }
}