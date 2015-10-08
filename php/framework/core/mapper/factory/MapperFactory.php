<?php

class MapperFactory
{
    const COMMAND_MAPPER = 'commandMapper';
    const ROUTING_MAPPER = 'routingMapper';

    public static function getMapper( $type )
    {
        $mapper = null;

        switch( $type )
        {
            case self::COMMAND_MAPPER:
                $mapper = Singleton::getInstance( CommandMapper::class );
                break;

            case self::ROUTING_MAPPER:
                $mapper = Singleton::getInstance( RoutingMapper::class );
                break;
        }

        return $mapper;
    }
}