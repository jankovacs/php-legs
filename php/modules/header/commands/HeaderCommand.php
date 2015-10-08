<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 11:54
 */
class HeaderCommand
{
    public function execute( $payload )
    {
        new HeaderView();
        $registerViewContentSignal = Singleton::getInstance( RegisterViewContentSignal::class );
        $registerViewContentSignal->dispatch( [ 'header', 'header/views/template.tpl' ] );
    }
}