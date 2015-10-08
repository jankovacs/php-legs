<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 21:05
 */
class RoutingCommand
{
    public function execute( $routing )
    {
        $routingModel = Singleton::getInstance( RoutingModel::class );
        $routingService = new RoutingService();
        $routingService->loadJsonSettings( __DIR__ . '/../../../config/routings.json' );
        $routingModel->setActualPage( $routing[ 0 ], $routingService->parse() );
    }
}