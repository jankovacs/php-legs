<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 22:55
 */
class RegisterViewContentCommand
{
    public function execute( $payload )
    {
        $payload = $payload[ 0 ];
        $viewComponentManager = Singleton::getInstance( ViewComponentManager::class );
        $viewComponentManager->addTemplateToPlace( $payload[ 0 ], $payload[ 1 ] );
    }
}