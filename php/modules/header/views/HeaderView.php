<?php

/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2015.04.05.
 * Time: 23:07
 */
class HeaderView
{
    public function __construct()
    {
        $smarty = Singleton::getInstance( 'Smarty' );
        $smarty->assign( 'h1href', 'http://testsite.net' );
        $smarty->assign( 'h1title', 'Test site' );
    }
}