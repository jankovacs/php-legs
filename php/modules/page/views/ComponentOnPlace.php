<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.06.
 * Time: 14:05
 */
class ComponentOnPlace
{
    private $componentsAddedToPlaces;

    public function __construct()
    {
        $this->componentsAddedToPlaces = [ ];
    }

    public function add( $place, $component )
    {
        if ( isset( $this->componentsAddedToPlaces[ $place ] ) )
        {
            $componentList = $this->componentsAddedToPlaces[ $place ];
        } else
        {
            $componentList = [ ];
        }

        $componentList[ ] = $component;

        $this->componentsAddedToPlaces[ $place ] = $componentList;
    }

    public function render()
    {
        $smarty = Singleton::getInstance( Smarty::class );
        foreach ( $this->componentsAddedToPlaces as $key => $value )
        {
            $smarty->assign( $key, implode( '', $value ) );
        }
    }
}