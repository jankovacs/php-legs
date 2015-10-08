<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.06.
 * Time: 14:25
 */
class AddComponentToPlaceCommand
{
    public function execute( $payload )
    {
        $place = $payload[ 0 ][ 0 ];
        $component = $payload[ 0 ][ 1 ];
        $viewComponentManager = Singleton::getInstance( ViewComponentManager::class );
        $viewComponentManager->addComponentToPlace( $place, $component );
    }
}