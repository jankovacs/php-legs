<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.04.
 * Time: 22:36
 */
class SetPageCommand
{
    public function execute( $payload )
    {
        $routingModel = Singleton::getInstance( RoutingModel::class );
        $pageFactory = new PageFactory();
        $actualPage = $pageFactory->createPage( $payload[ 0 ] );
        $actualPage->setPageVO( $routingModel->getPageVO( $payload[ 0 ] ) );
        $actualPage->render();
    }
}