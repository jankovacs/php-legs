<?php

/**
 * This class implements the Singleton pattern.
 * Please keep in mind that for proper use it needs an autoloader class.
 *
 * @author: Jan Kovács
 * Date: 2015.04.03.
 * Time: 10:40
 */

namespace pattern;

class Singleton
{
    private static $instances = [ ];

    final public static function registerIntsance( $className, $instance )
    {
        Singleton::$instances[ $className ] = $instance;
    }

    final public static function getInstance( $className )
    {
        $instance = Singleton::getConcreteInstance( $className );

        if ( !$instance )
        {
            $instance = Singleton::createInstance( $className );
        }

        return $instance;
    }

    private static function getConcreteInstance( $className )
    {
        $instance = null;
        foreach ( Singleton::$instances as $key => $value )
        {
            if ( $value && get_class( $value ) === $className )
            {
                $instance = $value;
                break;
            }
        }
        return $instance;
    }

    private static function createInstance( $className )
    {
        return Singleton::$instances[ ] = Singleton::getNewClass( $className );
    }

    private static function getNewClass( $className )
    {
        return new $className;
    }
}